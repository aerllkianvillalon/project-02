<?php
// app/Models/Document.php

require_once ROOT . '/core/Model.php';

class Document extends Model
{
    protected static string $table = 'documents';

    public function forApplication(int $applicationId): array
    {
        return $this->where(['application_id' => $applicationId]);
    }

    public function addDocument(int $applicationId, string $type, string $filePath, string $originalName): int
    {
        return $this->insert([
            'application_id' => $applicationId,
            'doc_type'       => $type,
            'file_path'      => $filePath,
            'original_name'  => $originalName,
            'uploaded_at'    => date('Y-m-d H:i:s'),
        ]);
    }
}
