$.ajax({
    url: '../database/data.php',
    method: 'get',
    headers: {'type': 'start'},
    success: function(data) {
        print(JSON.parse(data));
    }
});  

function change() {
    var form = document.forms.filter.elements;
    var class_checked = [];
    var engine_checked = [];
    var transmision_checked = [];
    for (var i = 2; i <= 9; i++) {
        if (form[i].checked) class_checked.push(form[i].labels[0].textContent);
    }
    for (var i = 10; i <= 13; i++) {
        if (form[i].checked) engine_checked.push(form[i].labels[0].textContent);
    }
    for (var i = 14; i <= 17; i++) {
        if (form[i].checked) transmision_checked.push(form[i].labels[0].textContent);
    }
    var result = new Object();
    result.price_start = form[0].value;
    result.price_end = form[1].value;
    result.classes = class_checked;
    result.engines = engine_checked;
    result.transmissions = transmision_checked;
    $.ajax({
        url: '../database/data.php',
        method: 'get',
        headers: {
            'type': 'filter'
        },
        data: result,
        cache: false,
        success: function(data) {
            print(JSON.parse(data));
        }
    });
}

function print(data) {
    var doc = document.getElementById('mainDiv');
    doc.innerHTML = '';
    var tr0 = document.createElement('tr');
    tr0.className = 'product';
    var td0 = document.createElement('td');
    td0.className = 'product_position';
    td0.textContent = 'Название';
    tr0.append(td0);
    var td1 = document.createElement('td');
    td1.className = 'product_position';
    td1.textContent = 'Цена';
    tr0.append(td1);
    var td2 = document.createElement('td');
    td2.className = 'product_position';
    td2.textContent = 'Класс';
    tr0.append(td2);
    var td3 = document.createElement('td');
    td3.className = 'product_position';
    td3.textContent = 'Тип двигателя';
    tr0.append(td3);
    var td4 = document.createElement('td');
    td4.className = 'product_position';
    td4.textContent = 'Коробка передач';
    tr0.append(td4);
    doc.append(tr0);
    for (var i = 0; i < Object.keys(data).length; i++) {
        var tr = document.createElement('tr');
        tr.className = 'product';
        for (var j = 0; j < 5; j++) {
            var td = document.createElement('td');
            td.className = 'product_position';
            td.textContent = data[i][j];
            tr.append(td);
        }
        doc.append(tr);
    }
}