/* Chinese translation for the jQuery Timepicker Addon */
/* Written by Alang.lin */
(function($) {
	$.timepicker.regional['zh-TW'] = {
		timeOnlyTitle: '選擇時分秒',
		timeText: '時間',
		hourText: '時',
		minuteText: '分',
		secondText: '秒',
		millisecText: '毫秒',
		microsecText: '微秒',
		timezoneText: '時區',
		currentText: '現在時間',
		closeText: '確定',
		timeFormat: 'HH:mm',
		timeSuffix: '',
		amNames: ['上午', 'AM', 'A'],
		pmNames: ['下午', 'PM', 'P'],
		isRTL: false,
		yearSuffix: '年',
		currentText: '今天',
		monthNames: ['一月','二月','三月','四月','五月','六月',
		'七月','八月','九月','十月','十一月','十二月'],
		monthNamesShort: ['一月','二月','三月','四月','五月','六月',
		'七月','八月','九月','十月','十一月','十二月'],
		dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
		dayNamesShort: ['週日','週一','週二','週三','週四','週五','週六'],
		dayNamesMin: ['日','一','二','三','四','五','六'],
		weekHeader: '週',
		showMonthAfterYear: true,

	};
	$.timepicker.setDefaults($.timepicker.regional['zh-TW']);
})(jQuery);
