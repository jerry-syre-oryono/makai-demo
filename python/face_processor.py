#!/usr/bin/env python3
"""
Face Recognition System using DeepFace and Qdrant
Place this file in: Cython\.face_processor.py
"""

import sys
import json
import cv2
import numpy as np
from deepface import DeepFace
from qdrant_client import QdrantClient
from qdrant_client.http.models import VectorParams, Distance, PointStruct
import os
from datetime import datetime
import hashlib
import base64
from PIL import Image
import io
import logging

# Setup logging
logging.basicConfig(
    filename='C:\\xampp\\htdocs\\makai-demo\\logs\\face_processor.log',
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

class FaceRecognitionSystem:
    def __init__(self):
        """Initialize the face recognition system"""
        try:
            # Initialize Qdrant client (running in Docker)
            self.qdrant = QdrantClient(host="localhost", port=6333)
            self.collection_name = "faces"
            
            # Create collection if it doesn't exist
            self.setup_collection()
            
            logging.info("Face Recognition System initialized successfully")
        except Exception as e:
            logging.error(f"Initialization error: {str(e)}")
            raise
    
    def setup_collection(self):
        """Setup Qdrant collection for face embeddings"""
        try:
            # Check if collection exists
            collections = self.qdrant.get_collections()
            exists = any(c.name == self.collection_name for c in collections.collections)
            
            if not exists:
                # Create collection with 512-dimensional vectors (FaceNet output)
                self.qdrant.create_collection(
                    collection_name=self.collection_name,
                    vectors_config=VectorParams(
                        size=512,
                        distance=Distance.COSINE
                    )
                )
                logging.info(f"Created collection: {self.collection_name}")
        except Exception as e:
            logging.error(f"Collection setup error: {str(e)}")
    
    def detect_faces(self, image_path):
        """
        Detect faces in an image
        Returns list of face images and their locations
        """
        try:
            # Load the image
            img = cv2.imread(image_path)
            if img is None:
                raise Exception(f"Could not read image: {image_path}")
            
            # Convert to RGB (DeepFace expects RGB)
            img_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            
            # Use OpenCV's face detector
            face_cascade = cv2.CascadeClassifier(
                cv2.data.haarcascades + 'haarcascade_frontalface_default.xml'
            )
            
            # Convert to grayscale for detection
            gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
            
            # Detect faces
            faces = face_cascade.detectMultiScale(
                gray,
                scaleFactor=1.1,
                minNeighbors=5,
                minSize=(30, 30)
            )
            
            face_images = []
            face_locations = []
            
            for (x, y, w, h) in faces:
                # Extract face region
                face = img_rgb[y:y+h, x:x+w]
                face_images.append(face)
                face_locations.append((x, y, w, h))
            
            logging.info(f"Detected {len(faces)} faces in {image_path}")
            return face_images, face_locations
            
        except Exception as e:
            logging.error(f"Face detection error: {str(e)}")
            return [], []
    
    def generate_embedding(self, face_image):
        """
        Generate face embedding using DeepFace
        Returns 512-dimensional vector
        """
        try:
            # Save face temporarily
            temp_path = "C:\\xampp\\htdocs\\makai-demo\\uploads\\temp\\temp_face.jpg"
            
            # Convert numpy array to image and save
            if isinstance(face_image, np.ndarray):
                # If it's a numpy array (from OpenCV)
                img = Image.fromarray(face_image)
                img.save(temp_path)
            else:
                # If it's a file path
                temp_path = face_image
            
            # Generate embedding using FaceNet model
            embedding = DeepFace.represent(
                img_path=temp_path,
                model_name="Facenet",
                enforce_detection=False,
                detector_backend='opencv'
            )[0]["embedding"]
            
            # Clean up temp file
            if os.path.exists(temp_path) and 'temp' in temp_path:
                os.remove(temp_path)
            
            return embedding
            
        except Exception as e:
            logging.error(f"Embedding generation error: {str(e)}")
            return None
    
    def add_face(self, image_path, person_name, additional_info=None):
        """
        Add a face to the database
        """
        try:
            # Detect faces
            faces, locations = self.detect_faces(image_path)
            
            if len(faces) == 0:
                return {"error": "No face detected in the image"}
            
            # Generate embedding for the first face
            embedding = self.generate_embedding(faces[0])
            
            if embedding is None:
                return {"error": "Failed to generate face embedding"}
            
            # Create unique ID
            point_id = hashlib.md5(
                f"{person_name}_{datetime.now().isoformat()}".encode()
            ).hexdigest()
            
            # Prepare payload
            payload = {
                "name": person_name,
                "image_path": image_path,
                "timestamp": datetime.now().isoformat(),
                "face_location": {
                    "x": int(locations[0][0]),
                    "y": int(locations[0][1]),
                    "width": int(locations[0][2]),
                    "height": int(locations[0][3])
                } if locations else None,
                "additional_info": additional_info or {}
            }
            
            # Store in Qdrant
            point = PointStruct(
                id=point_id,
                vector=embedding,
                payload=payload
            )
            
            self.qdrant.upsert(
                collection_name=self.collection_name,
                points=[point]
            )
            
            logging.info(f"Added face for {person_name}")
            
            return {
                "success": True,
                "face_id": point_id,
                "name": person_name,
                "message": f"Successfully added {person_name}"
            }
            
        except Exception as e:
            logging.error(f"Add face error: {str(e)}")
            return {"error": str(e)}
    
    def recognize_face(self, image_path, threshold=0.6):
        """
        Recognize faces in an image
        """
        try:
            # Detect faces
            faces, locations = self.detect_faces(image_path)
            
            if len(faces) == 0:
                return {"error": "No face detected in the image"}
            
            results = []
            
            for i, face in enumerate(faces):
                # Generate embedding for this face
                embedding = self.generate_embedding(face)
                
                if embedding is None:
                    continue
                
                # Search in Qdrant
                search_results = self.qdrant.search(
                    collection_name=self.collection_name,
                    query_vector=embedding,
                    limit=5,
                    score_threshold=threshold
                )
                
                matches = []
                for result in search_results:
                    matches.append({
                        "name": result.payload.get("name", "Unknown"),
                        "confidence": float(result.score),
                        "face_id": result.id,
                        "timestamp": result.payload.get("timestamp"),
                        "image_path": result.payload.get("image_path")
                    })
                
                # Save the detected face for reference
                face_filename = f"face_{datetime.now().strftime('%Y%m%d_%H%M%S')}_{i}.jpg"
                face_path = f"C:\\xampp\\htdocs\\makai-demo\\uploads\\recognized\\{face_filename}"
                
                # Convert numpy array to image and save
                img = Image.fromarray(face)
                img.save(face_path)
                
                results.append({
                    "face_index": i,
                    "location": {
                        "x": int(locations[i][0]),
                        "y": int(locations[i][1]),
                        "width": int(locations[i][2]),
                        "height": int(locations[i][3])
                    } if i < len(locations) else None,
                    "matches": matches,
                    "saved_face": f"/uploads/recognized/{face_filename}"
                })
            
            return {"success": True, "faces": results}
            
        except Exception as e:
            logging.error(f"Recognition error: {str(e)}")
            return {"error": str(e)}
    
    def find_similar_faces(self, person_name, limit=10):
        """
        Find all faces similar to a known person
        """
        try:
            # First, get one embedding for the person
            search_result = self.qdrant.scroll(
                collection_name=self.collection_name,
                scroll_filter={
                    "must": [
                        {"key": "name", "match": {"value": person_name}}
                    ]
                },
                limit=1
            )
            
            if not search_result[0]:
                return {"error": f"Person '{person_name}' not found in database"}
            
            # Get the vector from the first result
            reference_point = search_result[0][0]
            
            # Search for similar faces
            similar = self.qdrant.search(
                collection_name=self.collection_name,
                query_vector=reference_point.vector,
                limit=limit
            )
            
            results = []
            for hit in similar:
                results.append({
                    "name": hit.payload.get("name"),
                    "confidence": float(hit.score),
                    "image_path": hit.payload.get("image_path"),
                    "timestamp": hit.payload.get("timestamp"),
                    "face_id": hit.id
                })
            
            return {"success": True, "similar_faces": results}
            
        except Exception as e:
            logging.error(f"Find similar faces error: {str(e)}")
            return {"error": str(e)}
    
    def delete_face(self, face_id):
        """
        Delete a face from the database
        """
        try:
            self.qdrant.delete(
                collection_name=self.collection_name,
                points_selector=[face_id]
            )
            return {"success": True, "message": f"Deleted face {face_id}"}
        except Exception as e:
            logging.error(f"Delete error: {str(e)}")
            return {"error": str(e)}
    
    def list_all_faces(self, limit=100):
        """
        List all faces in the database
        """
        try:
            results = self.qdrant.scroll(
                collection_name=self.collection_name,
                limit=limit,
                with_payload=True,
                with_vectors=False
            )
            
            faces = []
            for point in results[0]:
                faces.append({
                    "face_id": point.id,
                    "name": point.payload.get("name"),
                    "timestamp": point.payload.get("timestamp"),
                    "image_path": point.payload.get("image_path")
                })
            
            return {"success": True, "faces": faces, "total": len(faces)}
            
        except Exception as e:
            logging.error(f"List error: {str(e)}")
            return {"error": str(e)}

# Command line interface
if __name__ == "__main__":
    # Initialize the system
    system = FaceRecognitionSystem()
    
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No command specified"}))
        sys.exit(1)
    
    command = sys.argv[1]
    
    try:
        if command == "add":
            # Usage: python face_processor.py add [image_path] [person_name] [additional_info_json]
            if len(sys.argv) < 4:
                print(json.dumps({"error": "Usage: add [image_path] [person_name] [additional_info]"}))

            image_path = sys.argv[2]
            person_name = sys.argv[3]
            additional_info = json.loads(sys.argv[4]) if len(sys.argv) > 4 else {}
            
            result = system.add_face(image_path, person_name, additional_info)
            print(json.dumps(result))
        
        elif command == "recognize":
            # Usage: python face_processor.py recognize [image_path] [threshold]
            if len(sys.argv) < 3:
                print(json.dumps({"error": "Usage: recognize [image_path] [threshold]"}))

            image_path = sys.argv[2]
            threshold = float(sys.argv[3]) if len(sys.argv) > 3 else 0.6
            
            result = system.recognize_face(image_path, threshold)
            print(json.dumps(result))
        
        elif command == "similar":
            # Usage: python face_processor.py similar [person_name] [limit]
            if len(sys.argv) < 3:
                print(json.dumps({"error": "Usage: similar [person_name] [limit]"}))

            person_name = sys.argv[2]
            limit = int(sys.argv[3]) if len(sys.argv) > 3 else 10
            
            result = system.find_similar_faces(person_name, limit)
            print(json.dumps(result))
        
        elif command == "list":
            # Usage: python face_processor.py list [limit]
            limit = int(sys.argv[2]) if len(sys.argv) > 2 else 100
            result = system.list_all_faces(limit)
            print(json.dumps(result))
        
        elif command == "delete":
            # Usage: python face_processor.py delete [face_id]
            if len(sys.argv) < 3:
                print(json.dumps({"error": "Usage: delete [face_id]"}))
                sys.exit(1)

            face_id = sys.argv[2]
            result = system.delete_face(face_id)
            print(json.dumps(result))
        
        else:
            print(json.dumps({"error": f"Unknown command: {command}"}))
            
    except Exception as e:
        print(json.dumps({"error": str(e)}))