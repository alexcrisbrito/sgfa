// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Montserrat' || 'sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
let pie = document.getElementById("myPieChart");
let myPieChart = new Chart(pie, {
  type: 'doughnut',
  data: {
    labels: labelspie,
    datasets: [{
      data: dataPie,
      backgroundColor: ColorsPie,
      hoverBackgroundColor: HoverPie,
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          return Intl.NumberFormat('de-DE')
              .format(chart.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]) + unitPie;
        }
      }
    },
    legend: {
      display: true,
      position: "bottom"
    },
    cutoutPercentage: 80,
  },
});
