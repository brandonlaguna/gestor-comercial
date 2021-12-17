<script>
<?php

switch ($type) {
    case 'Donut': ?>
        new Morris.Donut({
            element: '<?=$morrisId?>',
            data: [
                {label: "Men", value: 12},
                {label: "Women", value: 30},
                {label: "Kids", value: 20},
                {label: "Granparent", value: 29},
                {label: "Grandmother", value: 40},
                {label: "New", value: 40}
            ],
            colors: <?=json_encode($colors)?>,
            resize: true
        });
    <?php break;

    case 'Line': ?>
            new Morris.Line({
            element: '<?=$morrisId?>',
            data: [
                { y: '2006', a: 20, b: 10, c: 40, d:30},
                { y: '2007', a: 30, b: 15, c: 45, d:20},
                { y: '2008', a: 50, b: 40, c: 65, d:33,e:45},
                { y: '2009', a: 40, b: 25, c: 55, d:20},
                { y: '2010', a: 30, b: 15, c: 45, d:40},
                { y: '2011', a: 45, b: 20, c: 65, d:60},
                { y: '2012', a: 60, b: 40, c: 70, d:10, e:0},
                { y: '2012', a: 60, b: 40, c: 70, d:10},
                { y: '2012', a: 60, b: 40, c: 70, d:10}
            ],
            xkey: 'y',
            ykeys: ['a', 'b', 'c', 'd', 'e'],
            labels: ['Series A', 'Series B', 'Series C','Series D','Series E'],
            lineColors: ['#14A0C1', '#5058AB', '#72DF00','#74DF00','#DB6516'],
            lineWidth: 1,
            ymax: 'auto 100',
            gridTextSize: 11,
            hideHover: 'auto',
            resize: true
        });
    <?php  break;

    case 'Area': ?>
        new Morris.Area({
            element: '<?=$morrisId?>',
            data: [
                { y: '2006', a: 50, b: 40 },
                { y: '2007', a: 25,  b: 15 },
                { y: '2008', a: 20,  b: 40 },
                { y: '2009', a: 75,  b: 65 },
                { y: '2010', a: 50,  b: 40 },
                { y: '2011', a: 75,  b: 65 },
                { y: '2012', a: 100, b: 90 }
            ],
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['Series A', 'Series B'],
            lineColors: ['#14A0C1', '#5058AB'],
            lineWidth: 1,
            fillOpacity: 0.5,
            gridTextSize: 11,
            hideHover: 'auto',
            resize: true
        });
    <?php    break;

    case 'Bar': ?>
        new Morris.Bar({
        element: '<?=$morrisId?>',
        data: [
            { y: '2006', a: 100, b: 90, c: 80 },
            { y: '2007', a: 75,  b: 65, c: 75 },
            { y: '2008', a: 50,  b: 40, c: 45 },
            { y: '2009', a: 75,  b: 65, c: 85 },
        ],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['Series A', 'Series B', 'Series C'],
        barColors: ['#5058AB', '#14A0C1','#01CB99'],
        gridTextSize: 11,
        stacked: <?=$stacked?>,
        hideHover: 'auto',
        resize: true
        });
        
    <?php   break;

    default:
        # code...
        break;
}

?>

</script>