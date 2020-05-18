<?php

namespace MyApp;

class Calendar {
  public $prev;
  public $next;
  public $year;
  public $month;
  public $yearMonth;
  private $thisMonth;

  public function __construct() {
    try {
      if (!isset($_GET['t']) || !preg_match('/\A\d{4}-\d{2}\z/', $_GET['t'])) {
        throw new \Exception();
      }
      $this->thisMonth = new \DateTime($_GET['t']);
    } catch (\Exception $e) {
      $this->thisMonth = new \DateTime('first day of this month');
    }
    $this->prev = $this->_createPrevLink();
    $this->next = $this->_createNextLink();
    $this->year = $this->thisMonth->format('Y');
    $this->month = $this->thisMonth->format('n');
    $this->yearMonth = $this->thisMonth->format('F Y');
  }
  private function _createPrevLink() {
    $dt = clone $this->thisMonth;
    return $dt->modify('-1 month')->format('Y-m');
  }
  private function _createNextLink() {
    $dt = clone $this->thisMonth;
    return $dt->modify('+1 month')->format('Y-m');
  }
  public function show() {
    $tail = $this->_getTail();
    $body = $this->_getBody();
    $head = $this->_getHead();
    $html = '<tr class="date">' . $tail . $body . $head . '</tr>';
    echo $html;
  }
  private function _getTail() {
    $tail = '';
    $lastDayOfPrevMonth = new \DateTime('last day of' . $this->yearMonth. '-1 month');
    while ($lastDayOfPrevMonth->format('w') < 6) {
      $tail = sprintf('<td id="day_%d_before"class="gray">%d</td>',$lastDayOfPrevMonth->format('d'),$lastDayOfPrevMonth->format('d')).$tail;
      $lastDayOfPrevMonth->sub(new \DateInterval('P1D'));
    }
    return $tail;
  }
  private function _getBody() {
    $body = '';
    $period = new \DatePeriod(
      new \DateTime('first day of' .$this->yearMonth),
      new \DateInterval('P1D'),
      new \DateTime('first day of' .$this->yearMonth. '+1 month')
    );
    $today = new \DateTime('today');
    foreach ($period as $day) {
      if ($day->format('w') === '0') {
        $body .='</tr><tr class="date">';
      }
      $todayClass = ($day->format('Y-m-d') === $today->format('Y-m-d')) ? 'today' : '';
      $body .= sprintf('<td id="day_%02d"class="%s day_of_%d">%d</td>',$day->format('d'),$todayClass,$day->format('w'),$day->format('d'));
    }
    return $body;
  }
  private function _getHead() {
    $firstDayOfNextMonth = new \DateTime('first day of '.$this->yearMonth. '+1 month');
    $head = '';
    while ($firstDayOfNextMonth->format('w') > 0) {
      $head .= sprintf('<td id="day_%02d_next"class="gray">%d</td>',$firstDayOfNextMonth->format('d'),$firstDayOfNextMonth->format('d'));
      $firstDayOfNextMonth->add(new \DateInterval('P1D'));
    }
    return $head;
  }
}
