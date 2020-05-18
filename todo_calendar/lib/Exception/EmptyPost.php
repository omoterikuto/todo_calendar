<?php
namespace Myapp\Exception;

class EmptyPost extends \Exception {
  protected $message = 'メールアドレスとパスワードを入力してください';
}