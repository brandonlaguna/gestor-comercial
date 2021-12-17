<script>
<?php 
    switch ($type) {
        case 'Graph': ?>
            var ch3 = new Rickshaw.Graph({
            element: document.getElementById('<?=$morrisId?>'),
            renderer: 'area',
            max: 80,
            series: [{
            data: [
                { x: 0, y: 40 },
                { x: 1, y: 45 },
                { x: 2, y: 30 },
                { x: 3, y: 40 },
                { x: 4, y: 50 },
                { x: 5, y: 40 },
                { x: 6, y: 20 },
                { x: 7, y: 10 },
                { x: 8, y: 20 },
                { x: 9, y: 25 },
                { x: 10, y: 35 },
                { x: 11, y: 20 },
                { x: 12, y: 40 }
            ],
            color: 'rgba(255,255,255,0.5)'
            }]
        });
        ch3.render();
        // Responsive Mode
        new ResizeSensor($('.graphcontainer'), function(){
            ch3.configure({
            width: $('<?=$morrisId?>').width(),
            height: $('<?=$morrisId?>').height()
            });
            ch3.render();
        });
    <?php break;
        
        default:
            
            break;
    }
?>
</script>