 <?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\IncomeAnalyzer;

$incomeAnalyzer = new IncomeAnalyzer("root", "password");
$monthlyAmounts = $incomeAnalyzer->getMonthlyAmounts();
foreach ($monthlyAmounts as $monthAmount) {
  echo $monthAmount[0] . '月：' . $monthAmount[1];
  echo "<br />";
}
?>