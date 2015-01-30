var stat = function (id, name) {
    'use strict';

    this.id = id;
    this.name = name;
    this.data = [];
    this.others = 0;
};

stat.prototype.doughnut = function (data, total, type)
{
    'use strict';

    if (type === 'value') {
        var updated = (parseInt(data) / total) * 100;
        var notUpdated = 100 - updated;
        
        if (updated === 0) {
            this.data.push({y: notUpdated, label: 'Not updated' });
        } else {
            this.data.push({y: notUpdated, label: 'Not updated' });
            this.data.push({y: updated, label: 'Updated' });
        }
    } else {
        
        for (var i in data) {
            
            if (data.hasOwnProperty(i) && i < 5) {
                this.data.push({y: (parseInt(data[i].number) * 100) / total, label: data[i][this.id] });
            } else if (data.hasOwnProperty(i)) {
                this.others += (parseInt(data[i].number) * 100) / total;
                this.data[5] = {y: this.others, label: 'Others' };
            }
        }
    }

    return {
        title: {
            text: this.name,
            verticalAlign: 'top',
            horizontalAlign: 'center',
            fontSize: '16'
        },
        data: [
            {
                type: 'doughnut',
                startAngle: 20,
                toolTipContent: '#percent%',
                indexLabel: '{label} [#percent%]',
                dataPoints: this.data
            }
        ]
    };
};

stat.prototype.spline = function (data)
{
    'use strict';

    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    for (var i in data) {
        if (data.hasOwnProperty(i)) {
            var temp = {
                type: 'spline',
                showInLegend: true,
                name: i,
                dataPoints: []
            };

            for (var m in months) {
                if (data[i].hasOwnProperty(months[m])) {
                    temp.dataPoints.push({label: months[m], y: parseInt(data[i][months[m]])});
                } else {
                    temp.dataPoints.push({label: months[m], y: 0});
                }
            }

            this.data.push(temp);
        }
    }

    return {
        title: {
            text: this.name,
            fontSize: '18'
        },
        toolTip: {
            shared: true
        },
        axisX: {
            interval: 1
        },
        data: this.data
    };
};

stat.prototype.column = function (datas, color)
{
    'use strict';

    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    for (var i in datas) {
        if (datas.hasOwnProperty(i)) {
            var temp = {
                type: 'column',
                showInLegend: true,
                name: i,
                dataPoints: []
            };
            
            if (color !== undefined) {
                temp['color'] = color;
            }

            for (var m in months) {
                if (datas[i].hasOwnProperty(months[m])) {
                    temp.dataPoints.push({label: months[m], y: parseInt(datas[i][months[m]])});
                } else {
                    temp.dataPoints.push({label: months[m], y: 0});
                }
            }

            this.data.push(temp);
        }
    }

    return {
        title: {
            text: this.name,
            fontSize: '18'
        },
        toolTip: {
            shared: true
        },
        axisX: {
            interval: 1
        },
        data: this.data
    };
};