(function(){
    bpFieldInitSelect2FromAjaxPingMaps=(element)=>{
        var uniqueid=$(element).attr('uniqueid');
        if (!$(element).hasClass("select2-hidden-accessible"))
            {
                select2f=setSelect2Info(uniqueid);
                select2f['ajax']=setSelect2BasicAjax(uniqueid);
                select2f['ajax']['beforeSend']=function(xhr){};
                select2f['ajax']['data']=function(params){return bpFieldInitSelect2FromAjaxPingMaps_ForAjaxData(params)};
                select2f['ajax']['processResults']=function (data){return bpFieldInitSelect2FromAjaxPingMaps_ForAjaxProcessResults(data)};
                $(element).select2(select2f);
            }
    };
    formatState=(state) => {
        if (!state.id) { return state.text; }
        var $state = $(
            '<i class="fa fa-map-marker" aria-hidden="true"></i>' +
            state.text + ''
        );
        return $state;
    };
    bpFieldInitSelect2FromAjaxPingMaps_ForAjaxData=(params)=>{
        var query = {
            q:params.term,
            maxResults:25,
            key:pingMapsKey,
        }
        return query;
    };
    bpFieldInitSelect2FromAjaxPingMaps_ForAjaxProcessResults=(data)=>{
        if(!Array.isArray(data['resourceSets'])){return;}
        if(data['resourceSets'][0]['resources'].length == 0) {return;}
        var data = data['resourceSets'][0]['resources'];
        return {
            results: $.map(data, function (item,key) {
                var dod=''
                var addressarray=item['address'];
                var $adress=[];
                if(addressarray['adminDistrict4']){$adress.push(addressarray['adminDistrict4']);}
                if(addressarray['adminDistrict3']){$adress.push(addressarray['adminDistrict3']);}
                if(addressarray['adminDistrict2']){$adress.push(addressarray['adminDistrict2']);}
                if(addressarray['adminDistrict1']){$adress.push(addressarray['adminDistrict1']);}
                if(addressarray['adminDistrict']){$adress.push(addressarray['adminDistrict']);}
                dod+=" "+item.name+" ("+addressarray['countryRegion']+" - "+$adress.toString()+")";
                var coordinates=item.geocodePoints[0].coordinates;
                var mol=coordinates.toString()
                    return {
                        text:" "+item.name+" ("+addressarray['countryRegion']+" - "+$adress.toString()+")",
                        id:mol,

                    };
                })
        };
    
    };
})(jQuery);