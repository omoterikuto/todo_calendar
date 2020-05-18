<?php

namespace MyApp\Exception;

class DuplicateEmail extends \Exception {
  protected $message = 'すでに使用されているメールアドレスです。';
}