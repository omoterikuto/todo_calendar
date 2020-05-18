<?php
namespace Myapp\Exception;

class InvalidPassword extends \Exception {
  protected $message = 'パスワードが正しくありません';
}