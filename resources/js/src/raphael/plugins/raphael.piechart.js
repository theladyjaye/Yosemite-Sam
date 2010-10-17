Raphael.fn.piechart = function (cx, cy, r, values, labels, stroke, stroke_width, duration, show_labels, is_hoverable) {
    var paper = this,
        rad = Math.PI / 180,
        chart = this.set();
    function sector(cx, cy, r, startAngle, endAngle, params) {
        var x1 = cx + r * Math.cos(-startAngle * rad),
            x2 = cx + r * Math.cos(-endAngle * rad),
            y1 = cy + r * Math.sin(-startAngle * rad),
            y2 = cy + r * Math.sin(-endAngle * rad);
        return paper.path(["M", cx, cy, "L", x1, y1, "A", r, r, 0, +(endAngle - startAngle > 180), 0, x2, y2, "z"]).attr(params);
    }
    var angle = 90,
        total = 0,
        start = 0,
		colors = [["#f8faf3", "rgba(230, 230, 230, .2)"],
				  ["#08c4eb", "#07a6c7"]];
				  
						
        var process = function (j) {
            var txt,
				value = values[j],
                angleplus = 360 * value / total,
                popangle = angle + (angleplus / 2),					
                ms = duration,
                delta = 30,
                p = sector(cx, cy, r, angle, angle + angleplus, {gradient: "90-" + colors[j][1] + "-" + colors[j][0], stroke: stroke, "stroke-width": stroke_width});

			if(show_labels)
			{
               	txt = paper.text(cx + (0.4 * (r + delta - 8)) * Math.cos(-popangle * rad), cy + (r + delta - 5) * Math.sin(-popangle * rad), values[j] + "%").attr({fill: "#333", stroke: "none", opacity: 0, "font-family": 'Calibri, Lucida Sans, Helvetica, Arial', "font-size": "14px", "font-weight": "normal"});
			}

			if(is_hoverable)
			{
	            p.mouseover(function () {
	                p.animate({scale: [1.1, 1.1, cx, cy]}, ms, "backOut");
	                if(txt) txt.animate({opacity: 1}, ms, "backOut");
	            }).mouseout(function () {
	                p.animate({scale: [1, 1, cx, cy]}, ms, "backOut");
	                if(txt) txt.animate({opacity: 0}, ms);
	            });
			}
			
            angle += angleplus;
            chart.push(p);
            if(txt) chart.push(txt);
            start += .1;
        };
    for (var i = 0, ii = values.length; i < ii; i++) {
        total += values[i];
    }
    for (var i = 0; i < ii; i++) {
        process(i);
    }
    return chart;
};