var $portfoliosWrapper = $('#portfolios');
var $removePortfolio = $('.remove-portfolio');
var $removeStock = $('.remove-stock-quote');
var $getChartStockQuote = $('.get-chart-stock-quote');
var $getChartPortfolio = $('.get-chart-portfolio');
var $portfolioChart = $('.portfolio-chart');
var $portfolioForm = $('#portfolio-form');
var $inputSearch = $('#input-search input');
var $portfolioName = $('#portfolio-form-input-portfolio-name');
var $porfolioFormInputName = $('#portfolio-form-input-portfolio-name input');
var $portfolioFormStockList = $('#portfolio-form-stock-list');
var $add = $('#portfolio-form-stock-add');
var $persist = $('#portfolio-form-stock-persist');
var portfolioChartOptions = {
  explorer: {
    axis: 'horizontal',
    maxZoomIn: 50,
  },
  width: $(window).width(),
  height: $(window).height(),
  chartArea: {
    top: 68,
    left: 0,
    right: 0,
    bottom: 100,
  },
  legend: 'none',
  series: {
    0: {
      color: '#ffffff',
      lineWidth: 5,
      annotations: {
        textStyle: {
          color: '#ffffff',
          fontSize: 11,
          fontName: 'Ubuntu Condensed',
          bold: true,
          auraColor: '#365d3e',
        },
      },

    },
  },
  hAxis: {
    format: 'dd LLL',
    gridlines: {
      color: '#00ff66b8',
    },
    textStyle: {
      color: '#a5b6a9',
      fontSize: 11,
      bolder: true,
      fontName: 'Ubuntu Condensed',
    },
  },
  vAxis: {
    gridlines: {
      color: '#00ff66b8',
    },
    textStyle: {
      color: '#a5b6a9',
      fontSize: 11,
      fontName: 'Ubuntu Condensed',
    },
  },
  backgroundColor: 'none',
};
var stockQouteChartOptions = {
  chartArea: {
    top: 30,
    left: 30,
    right: 30,
    bottom: 30,
  },
  legend: 'none',
  series: {
    0: {
      color: '#ffffff',
      lineWidth: 1,
      annotations: {
        textStyle: {
          color: '#ffffff',
          fontSize: 15,
          fontName: 'Ubuntu Condensed',
          bold: true,
          auraColor: '#365d3e',
        },
      },
    },
  },
  hAxis: {
    format: 'dd LLL',
    gridlines: {
      color: 'none',
    },
    textStyle: {
      color: '#a5b6a9',
      fontSize: 11,
      bolder: true,
      fontName: 'Ubuntu Condensed',
    },
  },
  vAxis: {
    gridlines: {
      color: 'none',
    },
    textStyle: {
      color: '#a5b6a9',
      fontSize: 11,
      fontName: 'Ubuntu Condensed',
    },
  },
  backgroundColor: 'none',
};
$portfoliosWrapper.perfectScrollbar();
$portfolioForm.submit(function (e) {
  e.preventDefault();
  var data = JSON.stringify($(this).serializeJSON());
  var tmp = JSON.parse(data).stock_quotes;
  var stockQuotes = [];

  Object.keys(tmp).map(function (i) {
    stockQuotes.push(tmp[i].symbol.trim());
  });

  $.ajax({
    url: this.action,
    type: this.method,
    data: data,
    dataType: 'json',
    contentType: 'application/json',
    statusCode: {
      200: function (response) {
        console.log('200');
      },

      201: function (response) {
        response = JSON.parse(response);
        var html = '<i class="remove-portfolio" role="button" data-pid="' + response.id + '"></i>' +
          '<i class="get-chart-portfolio" role="button" data-pid="' + response.id + '" ' +
          'data-tooltip="chart" ' +
          'data-symbols=\'' + JSON.stringify(stockQuotes) + '\'></i>' +
          '<h2>' + response.name + '</h2><ul class="quotes-wrapper">';
        var prototype = $portfoliosWrapper.data('prototype');

        Object.keys(response.stock_quotes).map(function (i) {
          html += prototype
            .replace(/__sid__/g, response.stock_quotes[i].id)
            .replace(/__pid__/g, response.id)
            .replace(/__symbol__/g,  response.stock_quotes[i].symbol)
            .replace(/__code__/g,  response.stock_quotes[i].stock_exchange.code)
            .replace(/__companyName__/g,  response.stock_quotes[i].company.name);
        });

        html += '</ul>';

        $('<div class="row hidden portfolio-' + response.id + '">')
          .html(html).prependTo($portfoliosWrapper).slideDown(1000, function () {
            $(this).removeClass('hidden');

            $(this).find('.remove-portfolio').click(function (e) {
              callback = function (r) {
                $(this).closest('.portfolio-' + this.dataset.pid).remove();
                console.log('DELETED:' + this.dataset.pid);
              }.bind(this);

              $.ajax({
                type: 'DELETE',
                url: '/yahoo-finance/portfolio/' + this.dataset.pid,
                dataType: 'json',
                success: function (r) {
                  callback(r);
                },
              });
            });

            $(this).find('.remove-stock-quote').click(function (e) {
              callback = function (r) {
                $(this).closest('.stock-quote-' + this.dataset.sid).remove();
                console.log('DELETED:' + this.dataset.sid);
              }.bind(this);

              $.ajax({
                type: 'DELETE',
                url: '/yahoo-finance/portfolio/' + this.dataset.pid + '/' + this.dataset.sid,
                dataType: 'json',
                success: function (r) {
                  callback(r);
                },
              });
            });

            $(this).find('.get-chart-stock-quote').click(function (e) {
              var symbol = this.dataset.symbol;
              var chart = $(this).closest('.company-name').find('.chart')[0];

              $.ajax({
                type: 'POST',
                url: '/api/yahoo-finance/chart/quote',
                data: JSON.stringify({ symbol: symbol, startDate: '-1 week', endDate: 'now' }),
                dataType: 'json',
                success: function (dataChart) {
                  google.charts.setOnLoadCallback(
                    drawChart(dataChart, chart, stockQouteChartOptions)
                  );
                },
              });
            });

            $(this).find('.get-chart-portfolio').click(function (e) {
              var symbols = JSON.parse(this.dataset.symbols);
              var chart = $portfolioChart[0];

              $.ajax({
                type: 'POST',
                url: '/api/yahoo-finance/chart/portfolio',
                data: JSON.stringify({ symbols: symbols, startDate: '-2 year', endDate: 'now' }),
                dataType: 'json',
                success: function (dataChart) {
                  google.charts.setOnLoadCallback(
                    drawChart(dataChart, chart, portfolioChartOptions)
                  );
                },
              });
            });
          });

        $portfolioFormStockList.html('');
      },

      302: function (response) {
        console.log('302');
        callback(response, 'locationReload');
      },

      400: function (response) {
        console.log(response);
        console.log('400');
      },

      404: function (response) {
        console.log('404');
      },

      500: function (response) {
        console.log('500');
      },
    },
  });
});

$add.click(function (e) {
  e.preventDefault();
  $inputSearch.val('');
  var newWidget = this.dataset.prototype
    .replace(/__name__/g, $portfolioFormStockList.children().length)
    .replace(/__value__/g,  this.dataset.stockSymbol);

  var newItem = $('<li class="portfolio-form-stock-item"></li>').html(newWidget);
  newItem.prepend(
    '<h3>' + this.dataset.companyName + '</h3>' +
    '<span>' + this.dataset.typeDisp + ' - ' + this.dataset.exchDisp + '</span>' +
    '<i class="fa fa-times-circle form-search-remove-item" aria-hidden="true" role="button"></i>'
  );
  newItem.prependTo($portfolioFormStockList);
  $persist.attr('disabled', false);

  $('.form-search-remove-item').click(function () {
      e.preventDefault();
      $(this).closest('.portfolio-form-stock-item').remove();
    });

  delete this.dataset.companyName;
  delete this.dataset.exch;
  delete this.dataset.type;
  delete this.dataset.exchDisp;
  delete this.dataset.typeDisp;
  $(this).attr('disabled', 'disabled');
  $(this).removeClass('active');
});

$inputSearch[0].oninput = function () {
  $add.removeAttr('data-stock-persist'); //todo !!!!
  $add.removeClass('active');
};

$inputSearch.autocomplete({
  source: function (request, response) {
    var callback = function (res) {
      var suggestions = [];
      $.each(res, function (i, val) {
        // set property for autocomplete widget
        val.label = val.name + ' | ' + val.symbol + ' | ' + val.typeDisp + ' - ' + val.exchDisp;
        val.value = val.name;
        suggestions.push(val);
      });

      response(suggestions);
    };

    $.ajax({
      type: 'GET',
      url: '/api/yahoo-finance/search/' + request.term,
      dataType: 'json',
      success: function (r) {
        callback(r);
      },
    });
  },

  select: function (event, ui) {
    var i = ui.item;
    $inputSearch.blur();

    $add[0].dataset.stockSymbol = i.symbol;
    $add[0].dataset.companyName = i.name;
    $add[0].dataset.exch = i.exch;
    $add[0].dataset.type = i.type;
    $add[0].dataset.exchDisp = i.exchDisp;
    $add[0].dataset.typeDisp = i.typeDisp;
    $add.removeAttr('disabled');
    $add.addClass('active');
  },

  minLength: 2,
});

$portfolioFormStockList[0].addEventListener('DOMSubtreeModified', function (ev) {
  if (!this.children.length) {
    $persist.attr('disabled', true);
    $porfolioFormInputName.val('');
    $portfolioName.removeClass('active');
  } else {
    $portfolioName.addClass('active');
  }
});

$removePortfolio.click(function (e) {
  callback = function (r) {
    $(this).closest('.portfolio-' + this.dataset.pid).remove();
    console.log('DELETED:' + this.dataset.pid);
  }.bind(this);

  $.ajax({
    type: 'DELETE',
    url: '/yahoo-finance/portfolio/' + this.dataset.pid,
    dataType: 'json',
    success: function (r) {
      callback(r);
    },
  });
});

$removeStock.click(function (e) {
  callback = function (r) {
    $(this).closest('.stock-quote-' + this.dataset.sid).remove();
    console.log('DELETED:' + this.dataset.sid);
  }.bind(this);

  $.ajax({
    type: 'DELETE',
    url: '/yahoo-finance/portfolio/' + this.dataset.pid + '/' + this.dataset.sid,
    dataType: 'json',
    success: function (r) {
        callback(r);
      },
  });
});

$getChartStockQuote.click(function (e) {
  var symbol = this.dataset.symbol;
  var chart = $(this).closest('.company-name').find('.chart')[0];

  $.ajax({
    type: 'POST',
    url: '/api/yahoo-finance/chart/quote',
    data: JSON.stringify({ symbol: symbol, startDate: '-2 week', endDate: 'now' }),
    dataType: 'json',
    success: function (dataChart) {
      if (dataChart.length) {
        google.charts.setOnLoadCallback(drawChart(dataChart, chart, stockQouteChartOptions));
      }
    },
  });
});

$getChartPortfolio.click(function (e) {
  var symbols = JSON.parse(this.dataset.symbols);
  var chart = $portfolioChart[0];

  $.ajax({
    type: 'POST',
    url: '/api/yahoo-finance/chart/portfolio',
    data: JSON.stringify({ symbols: symbols, startDate: '-2 year', endDate: 'now' }),
    dataType: 'json',
    success: function (dataChart) {
      google.charts.setOnLoadCallback(drawChart(dataChart, chart, portfolioChartOptions));
    },
  });
});

function drawChart(dataChart, el, options) {
  $(el).perfectScrollbar({
    suppressScrollY: true,
    useBothWheelAxes: true,
    wheelSpeed: 5,
  });

  var data = new google.visualization.DataTable();

  data.addColumn({ type: 'date', role: 'domain', label: 'Дата', id: 'Date' });
  data.addColumn({ type: 'number', role: 'data', label: 'Открытие', id: 'Open' });
  data.addColumn({ type: 'number', role: 'interval', label: 'Максимум', id: 'High' });
  data.addColumn({ type: 'number', role: 'interval', label: 'Минимум', id: 'Low' });
  data.addColumn({ type: 'number', role: 'annotation', label: 'Закрытие', id: 'Close' });
  data.addColumn({ type: 'number', role: 'annotationText', label: '', id: 'Volume' });
  data.addColumn({ type: 'number', role: 'annotationText', label: '', id: 'AdjClose' });

  Object.keys(dataChart).map(function (i) {
    dataChart[i][0] = new Date(dataChart[i][0]);
  });

  data.addRows(dataChart);
  var chart = new google.visualization.LineChart(el).draw(data, options);
};
