<script src="assets/js/jquery-3.6.0.min.js"></script>

<script>

    let allBatmanGames, gamePrice = null;
    let allGameID = "";
    let gameAndPrice = [];

    const sortOnKey = (key, string, desc) => {
        const caseInsensitive = string && string === "CI";
        return (a, b) => {
            a = caseInsensitive ? a[key].toLowerCase() : a[key];
            b = caseInsensitive ? b[key].toLowerCase() : b[key];
            if (string) {
                return desc ? b.localeCompare(a) : a.localeCompare(b);
            }
            return desc ? b - a : a - b;
        }
    };

    allBatmanGames = $.extend({
        xResponse: function () {
            // local var
            var theResponse = null;
            // jQuery ajax
            $.ajax({
                url: "https://www.cheapshark.com/api/1.0/games?title=batman&limit=60&exact=0",
                method: "GET",
                mimeType: "multipart/form-data",
                contentType: "application/json",
                dataType: 'json',
                async: false,
                success: function (respText) {
                    theResponse = respText;
                }
            });
            // Return the response text
            return theResponse;
        }
    });

    $.each(allBatmanGames.xResponse(), function (i, item) {
        allGameID += item.gameID + ",";
    });

    gamePrice = $.extend({
        xResponse: function () {
            // local var
            var theResponse = null;
            // jQuery ajax
            $.ajax({
                url: "https://www.cheapshark.com/api/1.0/games?ids=" + allGameID,
                method: "GET",
                mimeType: "multipart/form-data",
                contentType: "application/json",
                dataType: 'json',
                async: false,
                success: function (respText) {
                    $.each(respText, function (i, item) {

                        gameAndPrice.push({
                            gameID: i,
                            gameTitle: item.info['title'],
                            gameThumb: item.info['thumb'],
                            price: item.deals[0]["price"],
                            retailPrice: item.deals[0]["retailPrice"],
                            savings: item.deals[0]["savings"]
                        });
                    });
                    theResponse = respText;
                }
            });
            // Return the response text
            return theResponse;
        }
    });

    gamePrice.xResponse();

    gameAndPrice = gameAndPrice.sort(sortOnKey("savings", false, true));
    gameAndPrice = gameAndPrice.filter(function (el) {
        return el.savings != "0.000000";
    });

    console.log(gameAndPrice);
    $.each(gameAndPrice, function (id, item) {

        let card = '<div class="col-sm-4 d-flex pb-3"><div class="card w-100">' +
            '<img class="card-img-top" src="' + item.gameThumb + '" alt="' + item.gameTitle + '">' +
            '<div class="card-body">' +
            '<h5 class="card-title">' + item.gameTitle + '</h5>' +
            '<p class="card-text"></p>' +
            '<span class="new_price price font-weight-bold font-italic">' + parseFloat(item.price).toFixed(2) + '₺</span>' +
            '<del class="old_price price text-danger">' + parseFloat(item.retailPrice).toFixed(2) + '₺</del>' +
            '<span class="badge badge-success savings p-2">%' + parseFloat(item.savings).toFixed(2) + '</span>' +
            '<button class="btn btn-success float-right">Sipariş</button>' +
            '</div> </div> </div>';

        $('#gameList').append(card);
    });
</script>


</body>
</html>