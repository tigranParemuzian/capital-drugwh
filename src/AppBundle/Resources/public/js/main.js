/**
 * Created by tigran on 1/27/17.
 */

getDataTerminals();
const GET = 'GET';
const POST = 'POST';
var slugItem;
var bookings = {};
function addByBag(getId, state, slug){
    if(typeof slug=='undefined'){
        slug = slugItem
    }
    var id=0, count;
    if(state == 0){
        id = parseInt(getId.replace(/add_/g, ''));
    } else if (state == 1){
        id = parseInt(getId.replace(/remove_/g, ''));
    }

    var my_input = $('#used_count_'+id);
    my_input.css({'border': "2px inset"}).closest('.grid-column-buy_count').children('div.help-text').html('');
    if(state == 1){
        count = 0;
    }else {

        count = my_input.val();
    }
    var data = {'slug': slug, 'count':count};
    sendRequest(data, POST);
    $('#add_'+id).hide();
}

function updateData(getId, status){

    var id = parseInt(getId.replace(/used_count_/g, '')), slug, count;

    var my_input = $('#used_count_'+id);
    if(my_input.val()<=0 && status == 0){
        my_input.css({'border': "red solid 1px"}).closest('.grid-column-buy_count').children('div.help-text').html('<span id="helpBlock" class="help-block" style="color: red">Please add count.</span>')
        $('#add_'+id).show();
    }else {
        var price = my_input.closest('tr').children('td.grid-column-price').text();
        count = my_input.val();
        var totl_itom = (parseFloat(price.substring(2)) * count).toFixed(2);

        if(status != 1){
            $('#add_'+id).show();
            my_input.css({'border': "2px inset"}).closest('.grid-column-buy_count').children('div.help-text').html('<b>$ '+ totl_itom +'</b>');
        }else {
            my_input.css({'border': "2px inset"}).closest('.grid-column-buy_count').children('div.help-text').html('');
        }

        var allCnt = my_input.closest('tr').children('td.grid-column-count').text();
        if(parseInt(allCnt) < count){
            $('#add_'+id).hide();
            my_input.css({'border': "2px inset"}).closest('.grid-column-buy_count').children('div.help-text').html('<b style="color: red">Count is limited '+ allCnt +'</b>');
        }
    }
}

function writeOrder(orders){
    var unt;
    switch (orders.product.product_item.unit){
        case 0:
            unt = ' ct';
            break;
        case 1:
            unt = ' ml';
            break;
        case 2:
            unt = ' s ea';
            break;
        case 3:
            unt = ' gm';
            break;
        default:
            break;

    }

    slugItem = orders.product.slug;

    $('table.order>tbody').append('<tr> <td >'+ orders.product.name +'</td>' +
        '<td>' + orders.product.product_item.strength +'</td>' +
        '<td>' + orders.product.product_item.size + ' ' + unt +'</td>' +
        '<td>$ ' + orders.product.price.toFixed(2) +'</td>'+
        '<td class="prod_count"><input id="order_id_'+ orders.product.id +'" type="text" value="' + orders.count +'" onkeydown="changeCount(this.id, 0)" onkeyup="changeCount(this.id, 0)" onchange="changeCount(this.id, 1)"> ' +
        '<button class="btn btn-success" style="display: none" type="button" id="order_add_'+ orders.product.id +'" onclick="submitAs('+orders.product.id+')">Add</button>' +
        '</td>'+
        '<td>$ ' + orders.sub_total +'</td>'+
        '<td> <button id="remove_' + orders.product.id +'" onclick="addByBag(this.id, 1)" class="btn btn-danger">' +
        '<span class="glyphicon glyphicon glyphicon-remove"></span>' +
        '</button>' +
        '</td>' +
        '</tr>')

}
/**
 * This function use to get data
 */
function getDataTerminals() {

    jQuery.ajax({
        url: "/api/bag/my/bag",
        type: "GET",
        contentType: 'application/json; charset=utf-8',
        async: true,
        success: function (resultData) {
            bookings = resultData;
            var result = resultData, counts;
            var total = 0;

            $('table.order>tbody').html('');
            for (var i = 0; i < result.length; i++)
            {
                total += parseFloat(result[i].sub_total);
                $('#used_count_'+result[i].product.id).val(result[i].count);
                writeOrder(result[i]);
            }

            $('.total-count').html('<span>Items Count </span> '+result.length);
            $('.total-price').html('<span>Total price </span>$ ' + total.toFixed(2));
            $('.selected_infos').html('Bag&nbsp;<span class="badge">'+result.length+'</span>')
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if(errorThrown==='Not Found'){
                $('table.order>tbody').html('');
                $('.total-count').html('<span>Items Count </span> 0');
                $('.total-price').html('<span>Total price </span>$ 0');
                $('.selected_infos').html('Bag&nbsp;<span class="badge">0</span>')
            }
            console.log(jqXHR, textStatus, errorThrown);
        }
    });
}

/**
 *
 * @param data
 * @param method
 */
function sendRequest(data, method)
{
    console.log(data);
    jQuery.ajax({
        url: '/api/bags/datas',
        type: "POST",
        contentType: 'application/json; charset=utf-8',
        async: true,
        dataType:"json",

        data: JSON.stringify(data),
        success:function(ansvwe)
        {
            getDataTerminals();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            /*if(jqXHR.status ===400){
                $('h4.modal-title').text('Cannot add');
                $('div.modal-body').html('<p>'+ jqXHR.responseJSON +'</p>');
                $('.modal').addClass('in').css({'display':'block'})
            }*/
            console.log(jqXHR, textStatus, errorThrown);
        }
    });
}

function changeCount(ids, state) {

    var id = ids.replace(/order_id_/g, '');

    $('#order_add_'+id).show();
    $('#used_count_'+id).val($('#'+ids).val());
}

function submitAs(id){

    bookings.forEach(function (element) {
        if(element.product.id == id){
            sendRequest({slug:element.product.slug, count:$('#order_id_'+id).val()})
        }
    });

}