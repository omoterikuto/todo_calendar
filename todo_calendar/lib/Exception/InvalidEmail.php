<?php
namespace Myapp\Exception;

class InvalidEmail extends \Exception {
  protected $message = 'メールアドレスが正しくありません';
}