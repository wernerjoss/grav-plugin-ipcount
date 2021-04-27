class IPCountPlotter {

	constructor() {
		this.subMonths = 1;
		this.chartJs = null;
		this.ipCount = ipcount;
	}

	initialize() {
		this.addEventListeners();
		this.initializeChart();
	}

	addEventListeners() {
		document.getElementById('showPrevMonth').addEventListener('click', () => this.showPreviousMonth());
		document.getElementById('showToday').addEventListener('click', () => this.showCurrentMonth());
	}

	showPreviousMonth() {
		const monthFilter = moment().subtract(this.subMonths, 'months').format('YYMM');

		this.subMonths++; // update for next click !

		const [dates, visitors] = this.filterData(monthFilter);
		this.drawChartJs(monthFilter, dates, visitors);

	}

	showCurrentMonth() {
		const monthFilter = moment().format('YYMM'); // today !
		this.subMonths = 1; // default value

		const [dates, visitors] = this.filterData(monthFilter);
		this.drawChartJs(monthFilter, dates, visitors);
	}

	filterData(monthfilter) {
		const days = this.ipCount['days'];
		const dates = [];
		const visitors = [];

		Object.entries(days).forEach((item) => {
			const date = item[0];
			const datum = date.substr(4, 2) + '.' + date.substr(2, 2) + '.' + date.substr(0, 2);
			const monthsig = date.substr(0, 2) + date.substr(2, 2);

			if (monthfilter.valueOf() == monthsig.valueOf()) {
				dates.push(datum);
				visitors.push(item[1]);
			}
		});

		return [dates, visitors];
	}

	initializeChart() {
		this.chartJs = new Chart(
			document.getElementById('ipChart'),
			{
				type: 'bar',
				data: {},
				options: {
					plugins: {
						legend: {
							display: false
						},
						title: {
							display: true,
						},
					},
					scales: {
						x: {
							type: 'category',
						}
					}
				}
			}
		);
	}

	drawChartJs(monthfilter, dates, visitors) {
		const month = monthfilter.substr(2, 2);
		const year = monthfilter.substr(0, 2);
		const title = 'Dayly Visitors Count for ' + month + ' / ' + year;
		const dayCount = moment(monthfilter, "YYMM").daysInMonth();

		const data = {
			labels: dates,
			datasets: [{
				label: 'Visitors',
				data: visitors,
			}]
		};

		this.chartJs.data = data;
		this.chartJs.options.plugins.title.text = title;
		this.chartJs.options.scales.x.labels = Array.from({ length: dayCount }, (_, i) => i + 1);
		this.chartJs.update();
	}
}

document.addEventListener('DOMContentLoaded', () => {
	const plotter = new IPCountPlotter();

	plotter.initialize();
	plotter.showCurrentMonth();
});
