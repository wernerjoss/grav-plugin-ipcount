class IPCountPlotter {

	constructor() {
		this.chartJs = null;
		this.ipCount = ipcount;
		this.date = new Date();
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
		this.date.setDate(0);

		const yearMonthFilter = this.getYearMonthFilter();

		const [dates, visitors] = this.filterData(yearMonthFilter);
		this.drawChartJs(yearMonthFilter, dates, visitors);

	}

	showCurrentMonth() {
		this.date = new Date();

		const yearMonthFilter = this.getYearMonthFilter();

		const [dates, visitors] = this.filterData(yearMonthFilter);
		this.drawChartJs(yearMonthFilter, dates, visitors);
	}

	getYearMonthFilter() {
		const monthNr = (this.date.getMonth() + 1).toString().padStart(2, '0');
		const year = `${this.date.getFullYear()}`.slice(2);

		return `${year}${monthNr}`;
	}

	getLastDayOfMonth() {
		return new Date(this.date.getFullYear(), this.date.getMonth() + 1, 0).getDate();
	}

	filterData(filterYearMonth) {
		const days = this.ipCount['days'];
		const lastDay = this.getLastDayOfMonth();

		const dates = Array.from({ length: lastDay }, (i) => i + 1);
		const visitors = Array.from({ length: lastDay }, () => 0);

		Object.entries(days).forEach((item) => {
			const date = item[0];

			const itemYearMonth = date.substr(0, 2) + date.substr(2, 2);

			if (filterYearMonth === itemYearMonth) {
				const dayIndex = Number(date.slice(-2)) - 1;
				visitors[dayIndex] = item[1];
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

	drawChartJs(yearMonthFilter, dates, visitors) {
		const year = yearMonthFilter.substr(0, 2);
		const month = yearMonthFilter.substr(2, 2);
		const title = 'Dayly Visitors Count for ' + month + ' / ' + year;
		const dayCount = this.getLastDayOfMonth();

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
