function gensql(all) {
    try {
        let sql = [`insert into cupidaddress(STATEVALUE   ,REORDER      ,ATTRIBUTEID  ,TRANSLATION  ,CHINESE      ,COUNTRYID    ,STATEID       )values  `]
        let values = []
        for (let it of all) {
            var  {STATEVALUE,
                REORDER,
                ATTRIBUTEID,
                TRANSLATION,
                COUNTRYID,
                STATEID}= it;
            var aa = [STATEVALUE,
                REORDER,
                ATTRIBUTEID,
                TRANSLATION,
                COUNTRYID,
                STATEID];
            values.push(JSON.stringify(aa).replace("[", "(").replace("]", ")"));
        }
        sql.push(values.join("\n,"))
        sql.push(";")
        return sql.join("");
    }catch (e) {
        console.log(e.toString())
    }
}

function spider(countyid) {
    let all = []
    return new Promise(function(resolve, reject){
        var curl = `https://www.chinalovecupid.com/en/widget/loadstates?countryid=${countyid}`;
        $.ajax({url: curl, dataType: "json"}).then(function (states) {
            let errorIds = []
            let count = 0;
            for (let it of states) {
                it.STATEID = null;
                it.COUNTRYID = countyid
                all.push(it)
                let stateid = it["ATTRIBUTEID"];
                var url = `https://www.chinalovecupid.com/en/widget/loadcities?stateid=${stateid}`;
                console.log("url", it["TRANSLATION"]);
                $.ajax({
                    url: url,
                    dataType: "json",
                    async: "false",
                    success: function (data) {
                        count++
                        for (let per of data) {
                            per.STATEID = stateid
                            per.COUNTRYID = countyid
                            all.push(per);
                        }
                        if (count == states.length) {
                            resolve(all)
                        }
                    }, error: function (e) {
                        errorIds.push(it["ATTRIBUTEID"]);
                    }
                })
            }
        })
    });

}
