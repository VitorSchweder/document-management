# API to manage PDF documents

## Instalation
- git clone;
- composer install;
- cp .env.example .env;
- php artisan key:generate;
- php artisan migrate;

## Main End-points
- Create Document Type: POST /api/types;
- Create Document Columns: POST /api/columns;
- Create Documents: POST /api/documents;
- Download documents: GET /api/documents/{id}/download;

### Insomnia file with main end-points is located in root called: Insomnia_2023-08-21.json 
