<?
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>
<?php
include("utils.php");
include_once("db.php");
$p_vote_id = gp("vi");
$query = "SELECT vo.vote_option_id, name, body 
					FROM vote_option vo, vote_vote_option vvo where vo.vote_option_id = vvo.vote_option_id and vvo.vote_id=" . $p_vote_id;
$result = $db->query($query);
$i = 1;
$caption = array();					
$ind = 0;
$count = 0;
$query2 = "SELECT count(*) as count from vote_ballot where vote_id = $p_vote_id" ;
$result2 = $db->query($query2);
$totalVotes = 0;	
if ($line2 = $db->fetchNextObject($result2))
{
	$totalVotes = $line2->count;
}
while ($line = $db->fetchNextObject($result))
{	
	$query = "SELECT count(*) as count from vote_ballot where vote_id = $p_vote_id and vote_option_id = $line->vote_option_id" ;
	$result2 = $db->query($query);
	
	if ($line2 = $db->fetchNextObject($result2))
	{
		$count2 =  $line2->count;
	}
	$caption[$ind] = 'Option #' . $i++ ;
	$data[$ind] = round($count2*100/$totalVotes);
	$ind++;
}
// generate some random data





include_once( 'ofc-library/open-flash-chart.php' );
$g = new graph();

//
// PIE chart, 60% alpha
//
$g->pie(60,'white','{font-size: 12px; color: white;background-color:white');
//
// pass in two arrays, one of data, the other data labels
//
$g->pie_values( $data,  $caption );
//
// Colours for each slice, in this case some of the colours
// will be re-used (3 colurs for 5 slices means the last two
// slices will have colours colour[0] and colour[1]):
//
$g->pie_slice_colours( array('#d01f3c','#356aa0','#C79810') );

$g->set_tool_tip( '#val#%' );

$g->title( '', '{font-size:18px; color: #d01f3c}' );
echo $g->render();
?>

