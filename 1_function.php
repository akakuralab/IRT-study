
<?php
class CustomError extends AssertionError
{ }

/**
 * 
 */
function RangeTest()
{
	// 変数
	$changeValue =  1.0;
	$lowerLimit = -3.0;
	$upperLimit =  3.0;

	echo "<h1>変数</h1>";
	echo "<h2>値</h2>";
	print("$changeValue=" . $changeValue . "<br>");
	print("$lowerLimit=" . $lowerLimit . "<br>");
	print("$upperLimit=" . $upperLimit . "<br>");
	echo "<h2>型</h2>";
	print("gettype($upperLimit)=" . gettype($upperLimit) . "<br>");


	echo "<h1>配列</h1>";
	$getValues = range($lowerLimit, $upperLimit, $changeValue);

	print($getValues);

	echo "<h2>For文</h2>";
	for ($i = 0; $i < count($getValues); $i++) {
		print($getValues[$i] . "<br>");
	}

	echo "<h2>ForEach文</h2>";
	foreach ($getValues as $getValue) {
		print($getValue . "<br>");
	}

	echo "<h2>print_r</h2>";
	print_r($getValues);

	echo "<h2>var_dump</h2>";
	var_dump($getValues);

	echo "<h2>型</h2>";
	print("gettype($getValues)=" . gettype($getValues) . "<br>");
}

/**
 * 正規分布の確率密度関数
 */
function ProbabilityDensityOfGaussianDistribution($value, $mean, $sd)
{
	return 1.0 / sqrt(2 * M_PI * pow($sd, 2)) * exp(-pow($value - $mean, 2) / pow(2 * $sd, 2));
}

/**
 * 標準正規分布の確率密度関数
 * @param {double} $value
 * @return {double}
 */
function ProbabilityDensityOfNormalDistribution($value)
{
	return ProbabilityDensityOfGaussianDistribution($value, 0, 1);
}

/**
 * 標準正規分布
 * @param {double} $lowerLimit 分布の下限
 * @param {double} $upperLimit 分布の下限
 * @param {double} $changeValue 離散の幅
 * @return {array}
 */
function NormalDistribution($lowerLimit, $upperLimit, $changeValue)
{
	$distribution = [];
	foreach (range($lowerLimit, $upperLimit, $changeValue) as $value)
		$distribution[] = ProbabilityDensityOfNormalDistribution($value) * $changeValue;
	return $distribution;
}

/**演習１
 * 標準正規分布のテスト
 */
function NormalDistributionTest()
{
	$correctValues = [0.042048206999253, 0.14676266317374, 0.31069656037693, 0.39894228040143, 0.31069656037693, 0.14676266317374, 0.042048206999253];

	$changeValue =  1.0;
	$lowerLimit = -3.0;
	$upperLimit =  3.0;

	$getValues = NormalDistribution($lowerLimit, $upperLimit, $changeValue);

	for ($i = 0; $i < count($getValues); $i++) {
		assert(
			is_float($getValues[$i]),
			new CustomError('値が浮動小数点型ではありません．' . gettype($getValues[$i]))
		);
		assert(
			abs($correctValues[$i] - $getValues[$i]) < 0.001,
			new CustomError('正規分布の値が異なります．' . $getValues[$i])
		);
	}
	assert(
		count($correctValues) === count($getValues),
		new CustomError('出力の次元が異なります．' . count($getValues))
	);
}

/**
 * ある能力値と項目特性が与えられたときの正答確率
 * @param {double} $ability_theta 能力値（θ）
 * @param {double} $itemQuarity_a 識別力（aパラメタ）
 * @param {double} $itemDifficulty_b 困難度（ｂパラメタ）
 * @return {array} 正答確率
 */
function CorrectProbability($ability_theta, $itemQuarity_a, $itemDifficulty_b)
{
	return 1.0 / (1.0 + exp(-1.7 * $itemQuarity_a * ($ability_theta - $itemDifficulty_b)));
}

/**
 * ある能力値と項目特性が与えられたときの反応確率
 * @param {int} $examineeResponse_x 反応（x）：正答はx=1，誤答はx=0
 * @param {double} $ability_theta 能力値（θ）
 * @param {double} $itemQuarity_a 識別力（aパラメタ）
 * @param {double} $itemDifficulty_b 困難度（ｂパラメタ）
 * @return {array} 正答確率
 */
function ResponseProbability($examineeResponse_x, $ability_theta, $itemQuarity_a, $itemDifficulty_b)
{
	$P = CorrectProbability($ability_theta, $itemQuarity_a, $itemDifficulty_b);
	return pow($P, $examineeResponse_x) * pow(1.0 - $P, 1 - $examineeResponse_x);
}

/**
 * 項目特性曲線
 * @param {int} $examineeResponse_x 反応（x）
 * @param {double} $ability_theta 能力値（θ）
 * @param {double} $itemQuarity_a 識別力（aパラメタ）
 * @param {double} $itemDifficulty_b 困難度（ｂパラメタ）
 * @param {double} $lowerLimit 分布の下限
 * @param {double} $upperLimit 分布の下限
 * @param {double} $changeValue 離散の幅
 * @return {array} 各能力値ごとの項目特性曲線配列
 */
function ItemCharacteristicCurve($examineeResponse_x, $itemQuarity_a, $itemDifficulty_b, $lowerLimit, $upperLimit, $changeValue)
{
	$ItemCharacteristicCurve = [];
	foreach (range($lowerLimit, $upperLimit, $changeValue) as $ability_theta) {
		$ItemCharacteristicCurve[] = ResponseProbability($examineeResponse_x, $ability_theta, $itemQuarity_a, $itemDifficulty_b);
	}
	return $ItemCharacteristicCurve;
}



/**演習２
 * 項目特性曲線のテスト
 */
function ItemCharacteristicCurveTest()
{
	$correctValues = [0.0060598014915841, 0.032295464698451, 0.15446526508353, 0.5, 0.84553473491647, 0.96770453530155, 0.99394019850842];

	// 正答テスト
	$changeValue =  1.0;
	$lowerLimit = -3.0;
	$upperLimit =  3.0;

	$examineeResponse_x = 1;
	$itemQuarity_a = 1.0;
	$itemDifficulty_b = 0.0;

	$getValues = ItemCharacteristicCurve($examineeResponse_x, $itemQuarity_a, $itemDifficulty_b, $lowerLimit, $upperLimit, $changeValue);

	for ($i = 0; $i < count($getValues); $i++) {
		assert(
			is_float($getValues[$i]),
			new CustomError('正答時の項目特性値が浮動小数点型ではありません．' . gettype($getValues[$i]))
		);
		assert(
			abs($correctValues[$i] - $getValues[$i]) < 0.001,
			new CustomError('正答時の項目特性曲線の値が異なります．' . $getValues[$i])
		);
	}

	// 誤答テスト
	$examineeResponse_x = 0;
	$itemQuarity_a = 1.0;
	$itemDifficulty_b = 0.0;

	$getValues = ItemCharacteristicCurve($examineeResponse_x, $itemQuarity_a, $itemDifficulty_b, $lowerLimit, $upperLimit, $changeValue);

	for ($i = 0; $i < count($getValues); $i++) {
		assert(
			is_float($getValues[$i]),
			new CustomError('誤答時の項目特性値が浮動小数点型ではありません．' . gettype($getValues[$i]))
		);
		assert(
			abs((1 - $correctValues[$i]) - $getValues[$i]) < 0.001,
			new CustomError('誤答時の項目特性曲線の値が異なります．' . $getValues[$i])
		);
	}
	assert(
		count($correctValues) === count($getValues),
		new CustomError('誤答時の項目特性曲線の出力の次元が異なります．' . count($getValues))
	);
}

/**
 * argmax関数
 * @param {array} $V 配列
 * @return {int} 最大値をとるインデックス
 */
function argmax($V)
{
	return array_search(max($V), $V);
}

/**
 * 回答された項目のパラメタと反応系列から能力値を推定
 * @param {array} $examineeResponse_x 配列
 * @param {array} $ItemBank [{"a"=>double,"b"=>double},]
 * @param {double} $lowerLimit 推定能力値の下限
 * @param {double} $upperLimit 推定能力値の下限
 * @param {double} $changeValue 能力値の推定精度
 * @return {array} 事後分布
 */
function Bayes($examineeResponses_X, $ItemBank, $lowerLimit, $upperLimit, $changeValue)
{
	$N = count($examineeResponses_X);
	$ability_theta = range($lowerLimit, $upperLimit, $changeValue);
	$lowerLimitength = count($ability_theta);

	$priorDistribution = NormalDistribution($lowerLimit, $upperLimit, $changeValue);
	$posteriorDistribution = $priorDistribution;

	for ($i = 0; $i < $N; $i++) {
		$item = $ItemBank[$i];
		$likelihood = ItemCharacteristicCurve(
			$examineeResponses_X[$i],
			$item["itemQuarity-a"],
			$item["itemDifficulty-b"],
			$lowerLimit,
			$upperLimit,
			$changeValue
		);
		for ($t = 0; $t < $lowerLimitength; $t++)
			$posteriorDistribution[$t] *= $likelihood[$t];
	}
	$evidence = array_sum($posteriorDistribution); //P(X)
	for ($t = 0; $t < $lowerLimitength; $t++)
		$posteriorDistribution[$t] /= $evidence; //P(X| θ ) P( θ)/ P(X)
	return $posteriorDistribution;
}


/**
 * 回答された項目のパラメタと反応系列から能力値を推定
 * @param {array} $examineeResponse_x 配列
 * @param {array} $ItemBank [{"a"=>double,"b"=>double},]
 * @param {double} $lowerLimit 推定能力値の下限
 * @param {double} $upperLimit 推定能力値の下限
 * @param {double} $changeValue 能力値の推定精度
 * @return {double} 能力値
 */
function AbilityEstimation($examineeResponses_X, $ItemBank, $lowerLimit, $upperLimit, $changeValue)
{
	$probability = Bayes($examineeResponses_X, $ItemBank, $lowerLimit, $upperLimit, $changeValue);
	$ability_theta = range($lowerLimit, $upperLimit, $changeValue);
	$ability_theta = $ability_theta[argmax($probability)];
	return $ability_theta;
}


function practice3()
{
	$changeValue =  1.0;
	$lowerLimit = -2.0;
	$upperLimit =  2.0;

	$ItemBank = [array("a" => 1, "b" => 0)];
	$examineeResponse_x = [1];

	$probability = Bayes($examineeResponse_x, $ItemBank, $lowerLimit, $upperLimit, $changeValue);
	foreach ($probability as $p)
		print($p . ",");

	$ability_theta = AbilityEstimation($examineeResponse_x, $ItemBank, $lowerLimit, $upperLimit, $changeValue);
	print("\nθ＝" . $ability_theta);
}
// practice3();


/**
 * 
 */
function EntropyCalculation($ability_theta, $ItemBank)
{
	$info = 0.0;
	foreach ($ItemBank as $item) {
		$P = CorrectProbability($ability_theta, $item["a"], $item["b"]);
		$info += 1.7 * 1.7 * $item["a"] * $item["a"] * $P * (1 - $P);
	}
	return $info;
}


function EstimErrorCalculation($ability_theta, $ItemBank)
{
	return 1.0 / sqrt(EntropyCalculation($ability_theta, $ItemBank));
}

function TestEntropyCalculation($ItemBank, $lowerLimit, $upperLimit, $changeValue)
{
	$ability_theta = range($lowerLimit, $upperLimit, $changeValue);
	$info = array_pad([], count($ability_theta), 0);
	for ($t = 0; $t < count($ability_theta); $t++)
		$info[$t] = EntropyCalculation($ability_theta[$t], $ItemBank);
	return $info;
}

function AdaptiveItemSelection($ability_theta, $ItemBank)
{
	$info = [];
	foreach ($ItemBank as $item)
		$info[] = EntropyCalculation($ability_theta, [array("a" => $item["a"], "b" => $item["b"])]);
	return argmax($info);
}
// print(AdaptiveItemSelection(0,[["a"=>0.3,"b"=>0],["a"=>1.0,"b"=>1.0],["a"=>1.0,"b"=>1.1]]));

function practice4()
{
	$info = EntropyCalculation(0, [array("a" => 1, "b" => 0)]);
	print_r($info);
}
// practice4();


/*課題１*/
//テスト情報量
function simulation($N, $J)
{

	$ItemBank = [];
	$i = 0;
	while ($i++ < $N) //アイテムバンク生成
		$ItemBank[] = array("a" => exp(RandomGauss(0.43, 0.20)), "b" => RandomGauss(-0.20, 1.16));

	$Test = $ItemBank; //アイテムバンクからテストを構成

	$Examinee = [];
	$j = 0;
	while ($j++ < $J) //受験者生成
		$Examinee[] = RandomGauss(0, 1);

	$error = []; //各受験者ごとの誤差
	foreach ($Examinee as $ability_theta) {
		$examineeResponse_x = []; //正誤
		foreach ($Test as $test) //乱数が正答確率を下回った？そうであれば正解：なければ不正解
			$examineeResponse_x[] = CorrectProbability($ability_theta, $test["a"], $test["b"]) > rand(0, 100) / 100 ? 1 : 0;
		$itemQuarity_abilityEstimation = AbilityEstimation($examineeResponse_x, $Test, -3, 3, 0.1);
		$error[] = abs($ability_theta - $itemQuarity_abilityEstimation);
	}
	return array_sum($error) / count($Examinee);
}
// $result = simulation(100,100);
// print("平均誤差：".$result."<br>");






// $results = [];
// $lowerLimitabels = [];
// for($i=1;$i<11;$i++){
// 	$result = simulation($i*10,100);
// 	$results[] = $result;
// 	$lowerLimitabels[] = $i*10;
// 	print("平均誤差：".$result."<br>");
// }

// $plt = new matplotlib();
// $plt->xlabel("出題項目数");
// $plt->ylabel("平均誤差");
// $plt->plot(array("Y"=>$results,"X"=>$lowerLimitabels));
// $plt->show();





function logArray($V)
{
	$lowerLimitogArray = [];
	foreach ($V as $v)
		$lowerLimitogArray[] = log($v);
	return $lowerLimitogArray;
}


function RandomGauss($itemQuarity_av, $sd)
{
	$examineeResponse_x = mt_rand() / mt_getrandmax();
	$y = mt_rand() / mt_getrandmax();
	return sqrt(-2 * log($examineeResponse_x)) * cos(2 * pi() * $y) * $sd + $itemQuarity_av;
}



if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
	// RangeTest();
	NormalDistributionTest();
	ItemCharacteristicCurveTest();
}
