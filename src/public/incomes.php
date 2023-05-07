 <?php


class IncomeAnalyzer {
  private $pdo;

  public function __construct($dbUserName, $dbPassword) {
    $this->pdo = new PDO("mysql:host=mysql; dbname=tq_quest; charset=utf8", $dbUserName, $dbPassword);
  }
  
  public function getMonthlyAmounts() {
    $sql = "SELECT * FROM incomes";
    $statement = $this->pdo->prepare($sql);
    $statement->execute();
    $incomes = $statement->fetchAll(PDO::FETCH_ASSOC);

    $all_row = [];
    foreach($incomes as $incomeKey => $income) {
      $all_row[$incomeKey] = [date('n',strtotime($income["accrual_date"])), $income["amount"]];
    }

    $monthes_amount = [];
    foreach($all_row as $row){
      $month = $row[0];
      $amount = $row[1];

      $index = $month - 1;
      if (!isset($monthes_amount[$index])) {
        $monthes_amount[$index] = [$month, 0];
      }
      $monthes_amount[$index][1] += $amount;
    }

    $amount_sort = [];
    foreach($monthes_amount as $key => $value){
      $amount_sort[$key] = $value[1];
    }

    array_multisort($amount_sort, SORT_DESC, $monthes_amount);

    $result = [];
    foreach($monthes_amount as $month_amount) {
      $result[] = [$month_amount[0], $month_amount[1]];
    }

    return $result;
  }
}


$incomeAnalyzer = new IncomeAnalyzer("root", "password");
$monthlyAmounts = $incomeAnalyzer->getMonthlyAmounts();
foreach ($monthlyAmounts as $monthAmount) {
  echo $monthAmount[0] . '月：' . $monthAmount[1];
  echo "<br />";
}























// array_multisort($sort, SORT_DESC, $incomes);

// foreach($incomes as $incomeBySort) {
//   echo $incomeBySort["amount"];
//   echo "<br />";
// }



// $dbUserName = "root";
// $dbPassword = "password";
// $pdo = new PDO("mysql:host=mysql; dbname=tq_quest; charset=utf8", $dbUserName, $dbPassword);

// $sql = "SELECT MONTH(date) as month, SUM(amount) as total_amount FROM incomes GROUP BY month ORDER BY total_amount DESC";
// $statement = $pdo->prepare($sql);
// $statement->execute();
// $incomesByMonth = $statement->fetchAll(PDO::FETCH_ASSOC);

// foreach($incomesByMonth as $incomeByMonth) {
//   echo "Month: " . $incomeByMonth["month"] . " Total Amount: " . $incomeByMonth["total_amount"];
//   echo "<br />";
// }


?>