<?php
include_once 'header.php';
?>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/d3.js"></script>
<?php
require_once('JunarApi.php');
$junarAPIClient = new Junar('d8aedab37ed88c5dfa86043a20aa52d90faabdbf');
$datastream = $junarAPIClient->datastream('ANNUA-BUDGE-BY-DEPAR'); // the guid (identificator of a datastream)
$response = $datastream->invoke($params = array(), $output = 'json_array');
$result = $response['result'];
print_r($result);
?>
<?php
function color (){
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

$color = random_color();
for($i=0;$i<=21;$i++){
    echo $color;
}
}
$mainArray = array();
foreach($result as $a){
    if($a[1]=='2013-2014'){
        $c = str_replace( ',', '', $a[3] );
        $tempArray = array('sector'=>$a[0], 'budget'=> trim($c, '$'));
        array_push($mainArray, $tempArray);
    }else{
        continue;
    }

}

?>
<script type="text/javascript">
    var data = <?php echo(json_encode($mainArray));?>;

    var width = 1100,
        height = 550,
        radius = Math.min(width, height) / 2;
    radius = 200;

    var color = d3.scale.ordinal()
        .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00", "#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#16a085", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50", "#f1c40f", "#e67e22", "#e74c3c", "#7f8c8d"]);

    var arc = d3.svg.arc()
        .outerRadius(200)
        .innerRadius(radius - 70);

    var pie = d3.layout.pie()
        .sort(null)
        .value(function (d) {
            return d.budget;
        });


    var svg = d3.select("body").append("svg")
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    var g = svg.selectAll(".arc")
        .data(pie(data))
        .enter().append("g")
        .attr("class", "arc");

    g.append("path")
        .attr("d", arc)
        .style("fill", function (d) {
            return color(d.data.sector);
        });

    g.append("text")
        .attr("transform", function (d) {
            return "translate(" + arc.centroid(d) + ")";
        })
        .attr("dy", ".35em")
        .style("text-anchor", "middle")
        .text(function (d) {
            return d.data.sector;
        });
</script>