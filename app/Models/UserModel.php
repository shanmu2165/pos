<?php

namespace App\models;
use CodeIgniter\Model;

class UserModel extends Model {
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';

    protected $allowedFields = ['name', 'email', 'password', 'site', 'perms', 'pos_id', 'status'];

}