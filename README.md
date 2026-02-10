# Makai Demo Project

## Project Overview

This project appears to be a PHP application, possibly involving a knowledge base or document processing, given the `knowledge` directory containing PDF files and the presence of `smalot/pdfparser` in the `vendor` directory. It also utilizes `composer` for PHP dependencies and `npm` (Node.js package manager) for frontend dependencies, indicated by `package.json` and `node_modules`. `Tailwind CSS` is likely used for styling, as suggested by `tailwind.config.js` and `src/input.css` / `styles/output.css`.

The presence of `ask.php`, `chat.php`, `embed.php`, `generate.php`, `index.php`, and `ingest.php` suggests functionalities related to:
- **`ingest.php`**: Likely processes and ingests documents (PDFs) into a system, possibly for search or Q&A.
- **`qdrant.php`**: Indicates integration with Qdrant, a vector similarity search engine, which would be used in conjunction with ingested documents for semantic search or retrieval-augmented generation (RAG).
- **`ask.php` / `chat.php`**: Suggests an interface for users to ask questions or chat with the system, possibly powered by the ingested knowledge.
- **`generate.php`**: Could be related to generating responses or content based on queries.
- **`embed.php`**: Might be used for creating embeddings of text from the documents, which are then stored in Qdrant.
- **`config.php`**: Project configuration.
- **`index.php`**: The main entry point of the web application.

## Setup and Installation

### Prerequisites

- PHP (version compatible with project dependencies)
- Composer
- Node.js and npm
- Qdrant (running instance or setup instructions)

### Backend Installation

1.  **Install PHP dependencies:**
    ```bash
    composer install
    ```
2.  **Configure `config.php`:**
    Edit `config.php` with your database connections, API keys, and Qdrant settings. (Specific details will depend on the actual contents of `config.php`).

### Frontend Installation

1.  **Install Node.js dependencies:**
    ```bash
    npm install
    ```
2.  **Compile Tailwind CSS:**
    ```bash
    npm run dev # or npm run build, depending on package.json scripts
    ```

### Qdrant Setup

Ensure your Qdrant instance is running and accessible as configured in `config.php`.

### Ingesting Knowledge

To process the PDF documents in the `knowledge/` directory, you would typically run the `ingest.php` script:

```bash
php ingest.php
```
(This might require command-line arguments or environment variables depending on its implementation).

## Usage

Once setup, navigate to `index.php` in your web server to access the application.

## Previous Issues and Fixes

*(Please provide details of previous issues and how they were fixed. This section will be updated with that information.)*

---

This `README.md` provides a general overview and basic setup instructions. More specific details for configuration, running Qdrant, and command-line usage of scripts like `ingest.php` would need to be extracted from the respective files.