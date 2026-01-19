<?php
/**
 * Classe Validator - Validation côté serveur
 * Centralise toutes les règles de validation pour garantir la sécurité des données
 */
class Validator
{
    private array $errors = [];
    private array $data = [];
    
    /**
     * Constructeur
     * @param array $data Données à valider
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }
    
    /**
     * Valide que le champ est requis (non vide)
     * @param string $field Nom du champ
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function required(string $field, string $label = null): self
    {
        $label = $label ?? $field;
        $value = $this->data[$field] ?? '';
        
        if (empty(trim($value))) {
            $this->errors[$field][] = "Le champ {$label} est obligatoire.";
        }
        
        return $this;
    }
    
    /**
     * Valide la longueur minimale
     * @param string $field Nom du champ
     * @param int $min Longueur minimale
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function minLength(string $field, int $min, string $label = null): self
    {
        $label = $label ?? $field;
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && mb_strlen(trim($value)) < $min) {
            $this->errors[$field][] = "{$label} doit contenir au moins {$min} caractères.";
        }
        
        return $this;
    }
    
    /**
     * Valide la longueur maximale
     * @param string $field Nom du champ
     * @param int $max Longueur maximale
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function maxLength(string $field, int $max, string $label = null): self
    {
        $label = $label ?? $field;
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && mb_strlen(trim($value)) > $max) {
            $this->errors[$field][] = "{$label} ne peut pas dépasser {$max} caractères.";
        }
        
        return $this;
    }
    
    /**
     * Valide un email
     * @param string $field Nom du champ
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function email(string $field, string $label = 'Email'): self
    {
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "{$label} n'est pas valide.";
        }
        
        return $this;
    }
    
    /**
     * Valide un numéro de téléphone français
     * @param string $field Nom du champ
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function phone(string $field, string $label = 'Téléphone'): self
    {
        $value = $this->data[$field] ?? '';
        
        if (!empty($value)) {
            $cleaned = preg_replace('/[\s\-\.]/', '', $value);
            if (!preg_match('/^(?:(?:\+|00)33|0)[1-9](?:\d{8})$/', $cleaned)) {
                $this->errors[$field][] = "{$label} n'est pas valide. Format attendu: 06 12 34 56 78 ou +33 6 12 34 56 78";
            }
        }
        
        return $this;
    }
    
    /**
     * Valide un nombre entier
     * @param string $field Nom du champ
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function integer(string $field, string $label = null): self
    {
        $label = $label ?? $field;
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$field][] = "{$label} doit être un nombre entier.";
        }
        
        return $this;
    }
    
    /**
     * Valide un nombre décimal positif
     * @param string $field Nom du champ
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function numeric(string $field, string $label = null): self
    {
        $label = $label ?? $field;
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && (!is_numeric($value) || $value < 0)) {
            $this->errors[$field][] = "{$label} doit être un nombre positif.";
        }
        
        return $this;
    }
    
    /**
     * Valide une valeur minimale
     * @param string $field Nom du champ
     * @param float $min Valeur minimale
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function min(string $field, float $min, string $label = null): self
    {
        $label = $label ?? $field;
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && is_numeric($value) && $value < $min) {
            $this->errors[$field][] = "{$label} doit être au minimum {$min}.";
        }
        
        return $this;
    }
    
    /**
     * Valide une valeur maximale
     * @param string $field Nom du champ
     * @param float $max Valeur maximale
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function max(string $field, float $max, string $label = null): self
    {
        $label = $label ?? $field;
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && is_numeric($value) && $value > $max) {
            $this->errors[$field][] = "{$label} ne peut pas dépasser {$max}.";
        }
        
        return $this;
    }
    
    /**
     * Valide qu'une valeur est dans une liste
     * @param string $field Nom du champ
     * @param array $values Valeurs autorisées
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function in(string $field, array $values, string $label = null): self
    {
        $label = $label ?? $field;
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !in_array($value, $values, true)) {
            $this->errors[$field][] = "{$label} contient une valeur non autorisée.";
        }
        
        return $this;
    }
    
    /**
     * Valide un code postal français
     * @param string $field Nom du champ
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function postalCode(string $field, string $label = 'Code postal'): self
    {
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !preg_match('/^[0-9]{5}$/', $value)) {
            $this->errors[$field][] = "{$label} doit être composé de 5 chiffres.";
        }
        
        return $this;
    }
    
    /**
     * Valide qu'un fichier a été uploadé
     * @param string $field Nom du champ
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function file(string $field, string $label = 'Fichier'): self
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            $this->errors[$field][] = "{$label} est obligatoire.";
        } elseif ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            $this->errors[$field][] = "Erreur lors de l'upload de {$label}.";
        }
        
        return $this;
    }
    
    /**
     * Valide le type MIME d'un fichier
     * @param string $field Nom du champ
     * @param array $mimes Types MIME autorisés
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function mimeType(string $field, array $mimes, string $label = 'Fichier'): self
    {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES[$field]['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mime, $mimes, true)) {
                $this->errors[$field][] = "{$label} doit être de type: " . implode(', ', $mimes);
            }
        }
        
        return $this;
    }
    
    /**
     * Valide la taille maximale d'un fichier
     * @param string $field Nom du champ
     * @param int $maxSize Taille maximale en octets
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function fileSize(string $field, int $maxSize, string $label = 'Fichier'): self
    {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            if ($_FILES[$field]['size'] > $maxSize) {
                $maxMb = round($maxSize / 1024 / 1024, 2);
                $this->errors[$field][] = "{$label} ne doit pas dépasser {$maxMb} Mo.";
            }
        }
        
        return $this;
    }
    
    /**
     * Valide que deux champs sont identiques
     * @param string $field Premier champ
     * @param string $matchField Champ à comparer
     * @param string $label Label pour les messages d'erreur
     * @return self
     */
    public function matches(string $field, string $matchField, string $label = null): self
    {
        $label = $label ?? $field;
        $value1 = $this->data[$field] ?? '';
        $value2 = $this->data[$matchField] ?? '';
        
        if ($value1 !== $value2) {
            $this->errors[$field][] = "{$label} ne correspond pas.";
        }
        
        return $this;
    }
    
    /**
     * Valide avec une expression régulière
     * @param string $field Nom du champ
     * @param string $pattern Pattern regex
     * @param string $message Message d'erreur personnalisé
     * @return self
     */
    public function regex(string $field, string $pattern, string $message): self
    {
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !preg_match($pattern, $value)) {
            $this->errors[$field][] = $message;
        }
        
        return $this;
    }
    
    /**
     * Valide avec une fonction personnalisée
     * @param string $field Nom du champ
     * @param callable $callback Fonction de validation
     * @param string $message Message d'erreur
     * @return self
     */
    public function custom(string $field, callable $callback, string $message): self
    {
        $value = $this->data[$field] ?? '';
        
        if (!$callback($value, $this->data)) {
            $this->errors[$field][] = $message;
        }
        
        return $this;
    }
    
    /**
     * Nettoie et sécurise une chaîne (protection XSS)
     * @param string $value Valeur à nettoyer
     * @return string
     */
    public static function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Vérifie si la validation a réussi
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }
    
    /**
     * Récupère toutes les erreurs
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Récupère les erreurs pour un champ spécifique
     * @param string $field Nom du champ
     * @return array
     */
    public function getError(string $field): array
    {
        return $this->errors[$field] ?? [];
    }
    
    /**
     * Récupère toutes les erreurs sous forme de chaîne
     * @param string $separator Séparateur entre les erreurs
     * @return string
     */
    public function getErrorsAsString(string $separator = '<br>'): string
    {
        $allErrors = [];
        foreach ($this->errors as $fieldErrors) {
            $allErrors = array_merge($allErrors, $fieldErrors);
        }
        return implode($separator, $allErrors);
    }
    
    /**
     * Ajoute une erreur manuellement
     * @param string $field Nom du champ
     * @param string $message Message d'erreur
     * @return self
     */
    public function addError(string $field, string $message): self
    {
        $this->errors[$field][] = $message;
        return $this;
    }
    
    /**
     * Réinitialise les erreurs
     * @return self
     */
    public function resetErrors(): self
    {
        $this->errors = [];
        return $this;
    }
    
    /**
     * Récupère les données validées et nettoyées
     * @return array
     */
    public function getValidatedData(): array
    {
        $validated = [];
        foreach ($this->data as $key => $value) {
            $validated[$key] = is_string($value) ? self::sanitize($value) : $value;
        }
        return $validated;
    }
}
