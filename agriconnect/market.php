<?php
$data = [
  "marketData" => [
    ["month" => "Jan", "tomatoPrice" => 25, "potatoPrice" => 12, "onionPrice" => 15],
    ["month" => "Feb", "tomatoPrice" => 27, "potatoPrice" => 14, "onionPrice" => 16],
    ["month" => "Mar", "tomatoPrice" => 29, "potatoPrice" => 16, "onionPrice" => 18],
    ["month" => "Apr", "tomatoPrice" => 28, "potatoPrice" => 15, "onionPrice" => 17],
    ["month" => "May", "tomatoPrice" => 31, "potatoPrice" => 18, "onionPrice" => 20],
    ["month" => "Jun", "tomatoPrice" => 33, "potatoPrice" => 20, "onionPrice" => 22],
  ],
  "cropData" => [
    ["crop" => "Wheat", "currentPrice" => 2800, "lastMonthPrice" => 2600],
    ["crop" => "Rice", "currentPrice" => 3200, "lastMonthPrice" => 3000],
    ["crop" => "Corn", "currentPrice" => 1800, "lastMonthPrice" => 1700],
    ["crop" => "Soybeans", "currentPrice" => 2400, "lastMonthPrice" => 2200],
  ],
  "yearlyPriceData" => [
    ["vegetable" => "Tomatoes", "2021" => 20, "2022" => 22, "2023" => 25, "2024" => 28],
    ["vegetable" => "Potatoes", "2021" => 10, "2022" => 12, "2023" => 15, "2024" => 18],
    ["vegetable" => "Onions", "2021" => 12, "2022" => 14, "2023" => 16, "2024" => 20],
    ["vegetable" => "Carrots", "2021" => 15, "2022" => 18, "2023" => 20, "2024" => 22],
    ["vegetable" => "Cabbage", "2021" => 18, "2022" => 20, "2023" => 22, "2024" => 25],
  ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Agricultural Market Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom, #f3e8ff, #d1fae5);
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      color: #2d3748;
      margin-bottom: 30px;
    }
    h2 {
      color: #2d3748;
      border-bottom: 2px solid #e2e8f0;
      padding-bottom: 8px;
    }
    section {
      margin-bottom: 40px;
    }
    .chart-wrapper {
      position: relative;
      margin-bottom: 60px;
    }
    .chart-container {
      position: relative;
      width: 100%;
      height: 360px;
      background:
        repeating-linear-gradient(90deg, transparent, transparent 99px, #eee 100px),
        repeating-linear-gradient(0deg, transparent, transparent 59px, #eee 60px);
      border-left: 2px solid #555;
      border-bottom: 2px solid #555;
      padding-top: 20px;
      padding-left: 40px;
      user-select: none;
    }
    svg { position: absolute; top:0; left:0; width:100%; height:100%; }
    
    /* Axis labels */
    .x-axis-label {
      position: absolute;
      width: 100%;
      text-align: center;
      bottom: -30px;
      font-weight: bold;
      font-size: 14px;
      color: #4a5568;
    }
    .y-axis-label {
      position: absolute;
      transform: rotate(90deg);
      transform-origin: left top;
      left: 100;
      top: 50%;
      font-weight: bold;
      font-size: 14px;
      color: #4a5568;
    }
    
    /* Chart elements */
    .line { fill:none; stroke-width:2; }
    .tomato { stroke:#FF6B6B; }
    .potato { stroke:#4ECDC4; }
    .onion  { stroke:#45B7D1; }
    circle { stroke:#333; stroke-width:1; }
    circle.tomato { fill:#FF6B6B; }
    circle.potato { fill:#4ECDC4; }
    circle.onion  { fill:#45B7D1; }
    
    /* Bars */
    .bar {
      position: absolute;
      bottom: 0;
      width: 40px;
      background: #8B5CF6;
      transition: background .2s;
      cursor: pointer;
    }
    .bar.last { background: #10B981; }
    .x-label {
      position: absolute;
      bottom: -25px;
      text-align: center;
      width: 80px;
      font-weight: bold;
      font-size: 12px;
      color: #4a5568;
    }
    .y-label {
      position: absolute;
      left: 0;
      text-align: right;
      width: 35px;
      font-size: 12px;
      padding-right: 5px;
      color: #4a5568;
    }
    
    /* Tooltip */
    .tooltip {
      position: absolute;
      padding: 10px 14px;
      background: rgba(0,0,0,0.9);
      color: #fff;
      border-radius: 6px;
      pointer-events: none;
      font-size: 14px;
      display: none;
      white-space: nowrap;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      z-index: 100;
      border: 1px solid rgba(255,255,255,0.2);
    }
    .tooltip strong { 
      display:block; 
      margin-bottom:6px; 
      font-size:16px;
      color: #f0f9ff;
    }
    .tooltip div { 
      margin-bottom:4px;
      color: #e2e8f0;
    }
    .tooltip .highlight {
      color: #fef08a;
      font-weight: bold;
    }
    
    /* Table styles */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: white;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px 15px;
      text-align: center;
      border: 1px solid #e2e8f0;
    }
    th {
      background-color: #4a5568;
      color: white;
      font-weight: bold;
    }
    tr:nth-child(even) {
      background-color: #f8fafc;
    }
    tr:hover {
      background-color: #f0f9ff;
    }
    .price-up {
      color: #10b981;
    }
    .price-down {
      color: #ef4444;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Agricultural Market Analysis Dashboard</h1>

    <!-- Vegetable Price Trends -->
    <section>
      <h2>Vegetable Price Trends (₹/kg)</h2>
      <div class="chart-wrapper">
        <div id="vegContainer" class="chart-container">
          <svg id="vegSVG"></svg>
          <?php
            // Add Y-axis labels for vegetable chart
            $steps = [10,15,20,25,30,35];
            $plotH = 360 - 40 - 20;
            $minV = min(
              min(array_column($data['marketData'], 'tomatoPrice')),
              min(array_column($data['marketData'], 'potatoPrice')),
              min(array_column($data['marketData'], 'onionPrice'))
            ) - 5;
            $maxV = max(
              max(array_column($data['marketData'], 'tomatoPrice')),
              max(array_column($data['marketData'], 'potatoPrice')),
              max(array_column($data['marketData'], 'onionPrice'))
            ) + 5;
            
            foreach($steps as $val) {
              $yPos = 20 + $plotH * (1 - ($val - $minV)/($maxV - $minV));
              echo "<div class='y-label' style='bottom:" . (360 - $yPos - 5) . "px'>₹$val</div>";
            }
          ?>
        </div>
        <div class="x-axis-label">Months</div>
        <div class="y-axis-label">Price (₹/kg)</div>
      </div>
    </section>

    <!-- Crop Price Comparison -->
    <section>
      <h2>Crop Price Comparison (₹/quintal)</h2>
      <div class="chart-wrapper">
        <div id="cropContainer" class="chart-container">
          <?php
            // Draw crop bars and Y-axis labels
            $plotW = 800 - 40 - 20;
            $barSpacing = $plotW / count($data['cropData']);
            $plotH = 360 - 40 - 20;
            
            // Find max crop price for scaling
            $maxCrop = max(
              max(array_column($data['cropData'], 'currentPrice')),
              max(array_column($data['cropData'], 'lastMonthPrice'))
            );
            $maxCrop = ceil($maxCrop / 500) * 500;
            
            // Add Y-axis labels
            $steps = 5;
            for ($i = 0; $i <= $steps; $i++) {
              $value = ($maxCrop / $steps) * $i;
              $yPos = 20 + $plotH * (1 - ($i / $steps));
              echo "<div class='y-label' style='bottom:" . (360 - $yPos - 5) . "px'>₹" . number_format($value) . "</div>";
            }
            
            // Draw bars
            foreach($data['cropData'] as $i => $c){
              $left = 40 + $i * $barSpacing + ( $barSpacing - 2*40 )/2;
              $h1 = ($c["currentPrice"] / $maxCrop) * $plotH;
              $h2 = ($c["lastMonthPrice"] / $maxCrop) * $plotH;
              echo "<div class='bar current' style='left:{$left}px; height:{$h1}px;' data-idx='{$i}'></div>";
              echo "<div class='bar last'    style='left:".($left+40)."px; height:{$h2}px;' data-idx='{$i}'></div>";
              echo "<div class='x-label' style='left:{$left}px;'>{$c['crop']}</div>";
            }
          ?>
        </div>
        <div class="x-axis-label">Crops</div>
        <div class="y-axis-label">Price (₹/quintal)</div>
      </div>
    </section>

    <!-- Yearly Price Data Table -->
    <section>
      <h2>Yearly Average Vegetable Prices (₹/kg)</h2>
      <table id="priceTable">
        <thead>
          <tr>
            <th>Vegetable</th>
            <th>2021</th>
            <th>2022</th>
            <th>2023</th>
            <th>2024</th>
            <th>Trend</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['yearlyPriceData'] as $row): 
            $trend = ($row['2024'] - $row['2021']) / $row['2021'] * 100;
            $trendClass = $trend >= 0 ? 'price-up' : 'price-down';
            $trendIcon = $trend >= 0 ? '↑' : '↓';
          ?>
            <tr>
              <td><?= $row['vegetable'] ?></td>
              <td>₹<?= $row['2021'] ?></td>
              <td>₹<?= $row['2022'] ?></td>
              <td>₹<?= $row['2023'] ?></td>
              <td>₹<?= $row['2024'] ?></td>
              <td class="<?= $trendClass ?>">
                <?= $trendIcon ?> <?= abs(round($trend, 1)) ?>%
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Shared Tooltip -->
    <div id="tooltip" class="tooltip"></div>
  </div>

  <script>
    // — VEGETABLE CHART —
    (function(){
      const data = {
        months: <?= json_encode(array_column($data['marketData'], 'month')) ?>,
        tomato: <?= json_encode(array_column($data['marketData'], 'tomatoPrice')) ?>,
        potato: <?= json_encode(array_column($data['marketData'], 'potatoPrice')) ?>,
        onion:  <?= json_encode(array_column($data['marketData'], 'onionPrice')) ?>
      };
      const container = document.getElementById("vegContainer");
      const svg = document.getElementById("vegSVG");
      const tooltip = document.getElementById("tooltip");
      const W = container.clientWidth, H = container.clientHeight;
      const M = {top:20,right:20,bottom:40,left:40};
      const plotW = W - M.left - M.right;
      const plotH = H - M.top  - M.bottom;
      const all = [...data.tomato, ...data.potato, ...data.onion];
      const minV = Math.min(...all)-5, maxV = Math.max(...all)+5;
      const xStep = plotW / (data.months.length - 1);
      const mapX = i=> M.left + xStep*i;
      const mapY = v=> M.top + plotH*(1-(v-minV)/(maxV-minV));

      // axes labels
      data.months.forEach((m,i)=>{
        let t=document.createElementNS(svg.namespaceURI,"text");
        t.setAttribute("x", mapX(i));
        t.setAttribute("y", M.top+plotH+20);
        t.setAttribute("text-anchor","middle");
        t.setAttribute("font-size","12");
        t.textContent=m;
        svg.appendChild(t);
      });

      // lines & dots
      function drawLine(arr,cls){
        let pts=arr.map((v,i)=>`${mapX(i)},${mapY(v)}`).join(" ");
        let poly=document.createElementNS(svg.namespaceURI,"polyline");
        poly.setAttribute("points",pts);
        poly.setAttribute("class","line "+cls);
        svg.appendChild(poly);
      }
      function drawDots(arr,cls){
        arr.forEach((v,i)=>{
          let c=document.createElementNS(svg.namespaceURI,"circle");
          c.setAttribute("cx", mapX(i));
          c.setAttribute("cy", mapY(v));
          c.setAttribute("r",4);
          c.setAttribute("class",cls);
          svg.appendChild(c);
        });
      }
      drawLine(data.tomato,"tomato");
      drawLine(data.potato,"potato");
      drawLine(data.onion,"onion");
      drawDots(data.tomato,"tomato");
      drawDots(data.potato,"potato");
      drawDots(data.onion,"onion");

      // tooltip on move - shows all three values for the month
      container.addEventListener("mousemove", e=>{
        const rect=container.getBoundingClientRect();
        const x=e.clientX-rect.left, y=e.clientY-rect.top;
        const xRel=x-M.left;
        if(xRel<0||xRel>plotW){ tooltip.style.display="none"; return; }
        
        // Find nearest month index
        const idx=Math.round(xRel/xStep);
        if(idx<0||idx>=data.months.length){ tooltip.style.display="none"; return; }
        
        const m=data.months[idx],
              t=data.tomato[idx],
              p=data.potato[idx],
              o=data.onion[idx];
        
        tooltip.innerHTML=`
          <strong>${m}</strong>
          <div>Tomatoes: <span class="highlight">₹${t}</span></div>
          <div>Potatoes: <span class="highlight">₹${p}</span></div>
          <div>Onions: <span class="highlight">₹${o}</span></div>
        `;
        tooltip.style.display="block";
        tooltip.style.left=`${e.clientX}px`;  // Exact X position
        tooltip.style.top=`${e.clientY}px`;   // Exact Y position
      });
      
      container.addEventListener("mouseleave", ()=>{ tooltip.style.display="none"; });
    })();

    // — CROP CHART —
    (function(){
      const cropData = <?= json_encode($data['cropData']) ?>;
      const container = document.getElementById("cropContainer");
      const tooltip = document.getElementById("tooltip");
      
      container.addEventListener("mousemove", e=>{
        const rect=container.getBoundingClientRect();
        const x=e.clientX-rect.left, y=e.clientY-rect.top;
        
        // find index by dividing by barSpacing
        const plotW = rect.width - 40 - 20;
        const barCount = cropData.length;
        const barFullWidth = plotW / barCount;
        const idx = Math.floor((x - 40) / barFullWidth);
        
        if(idx<0||idx>=barCount){ tooltip.style.display="none"; return; }
        
        // Check if mouse is near any bar
        const c = cropData[idx];
        const left = 40 + idx * barFullWidth + (barFullWidth - 2*40)/2;
        const right = left + 80;
        
        if(x < left || x > right) {
          tooltip.style.display = "none";
          return;
        }
        
        const change = c.currentPrice - c.lastMonthPrice;
        const changePercent = ((change / c.lastMonthPrice) * 100).toFixed(1);
        const changeClass = change >= 0 ? 'highlight' : 'price-down';
        
        tooltip.innerHTML = `
          <strong>${c.crop}</strong>
          <div>Current Month: <span class="highlight">₹${c.currentPrice.toLocaleString()}</span></div>
          <div>Previous Month: ₹${c.lastMonthPrice.toLocaleString()}</div>
          <div>Change: <span class="${changeClass}">₹${Math.abs(change).toLocaleString()} (${changePercent}%)</span></div>
        `;
        tooltip.style.display = "block";
        tooltip.style.left = `${e.clientX}px`;  // Exact X position
        tooltip.style.top = `${e.clientY}px`;   // Exact Y position
      });
      
      container.addEventListener("mouseleave", ()=>{ tooltip.style.display="none"; });
    })();
  </script>
</body>
</html>