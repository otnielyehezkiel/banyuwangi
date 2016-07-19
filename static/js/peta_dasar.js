var peta_dasar_base_url,
        peta_dasar_site_url,
        peta_dasar_infoWindow,
        peta_dasar_map;
var peta_dasar_warna = ["#E455CF", "#D500C7", "#7E6287", "#6A5778", "#95C044", "#1B8542", "#9F4C1A", "#9A335B", "#09BC02", "#76BE47", "#8E2214", "#09CFFC", "#60D1D3", "#812E0F", "#F11E8B", "#197CAC", "#824BC3", "#EEFAA4", "#60C71D", "#CFF838"];
var peta_dasar_warna2 = ["#3007C7", "#9F38E2", "#11055B", "#7DB43C", "#E68353", "#0EE174", "#7ED1F0", "#9F2E2C", "#F63003", "#71DDEB", "#1BAA30", "#2AFF38", "#819D78", "#95A887", "#6A3FBB", "#E47ABD", "#60B3E5", "#65CCA4", "#F643FD", "#8941B8"];
var peta_dasar_bound_kec = [];
var peta_dasar_path_kec = [];
var peta_dasar_bound_des = [];
var peta_dasar_path_des = [];
var peta_dasar_kec_to_hide = 0;
var peta_dasar_des_to_hide = 0;
function peta_dasar_initialize() {
    peta_dasar_base_url = document.getElementById("base_url").value;
    peta_dasar_site_url = document.getElementById("site_url").value;
    var map_longitude = document.getElementById('map_longitude').value;
    peta_dasar_infoWindow = new google.maps.InfoWindow();
    var letters = '0123456789ABCDEF'.split('');
    for (var h = 0; h < -10; h++) {
        var color = '#';
        var color2 = '#';
        for (var i = 0; i < 6; i++) {
            var r = Math.floor(Math.random() * 16);
            color += letters[15 - r ];
            color2 += letters[ r ];
        }
        peta_dasar_warna.push(color);
        peta_dasar_warna2.unshift(color2);
    }
    var myOptions = {
        zoom: 11,
        center: new google.maps.LatLng(-7.56, map_longitude),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.BOTTOM_LEFT
        },
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE,
            position: google.maps.ControlPosition.LEFT_BOTTOM
        },
        panControl: true,
        panControlOptions: {
            position: google.maps.ControlPosition.LEFT_BOTTOM
        },
        streetViewControl: false
    }
    peta_dasar_map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    peta_dasar_draw_poly_kab();

    peta_dasar_draw_poly_kec();
    var id_kec = document.getElementById('pilihkecamatan').value;
    load_potensi_kecamatan(id_kec);
}
function peta_dasar_draw_poly_kec() {
    console.log('peta_dasar_draw_poly_kec');
    var id_kec = document.getElementById('pilihkecamatan').value;
    $.ajax({
        type: "POST",
        url: peta_dasar_site_url + "/map/get_polygon_kecamatan/" + id_kec,
        dataType: 'json',
        success: function(json) {
            var c = json.length;
            var a = peta_dasar_path_des.length;
            //console.log(peta_dasar_path_des);
            for (var prop in peta_dasar_path_des) {
                if (peta_dasar_path_des.hasOwnProperty(prop)) {
                    //console.log('null untuk id ' + prop);
                    peta_dasar_path_des[prop].setMap(null);
                }
            }
            peta_dasar_path_des = [];
            for (var i = 0; i < c; i++) {
                var item = json[i];
                plot_des(item, i);
            }
            if (peta_dasar_path_kec.hasOwnProperty(peta_dasar_kec_to_hide)) {
                peta_dasar_path_kec[peta_dasar_kec_to_hide].setMap(null);
            }
        }
    });
}
function peta_dasar_draw_poly_kab() {
    console.log('peta_dasar_draw_poly_kab');
    $.ajax({
        type: "POST",
        url: peta_dasar_site_url + "/map/get_polygon_kabupaten",
        dataType: 'json',
        success: function(json) {
            var c = json.length;
            for (var i = 0; i < c; i++) {
                var item = json[i];
                plot_kec(item, i);
            }
        }
    });
}

function plot_des(wilayah, indeks) {
    var vertex = [];
    var vertices = wilayah.geo;
    var l = vertices.length;
    for (var i = 0; i < l; i++) {
        vertex[i] = new google.maps.LatLng(vertices[i].LAT, vertices[i].LNG);
    }
    var id = wilayah.id_des + ':'+wilayah.id_kec;
    peta_dasar_bound_des[id] = new google.maps.LatLngBounds();
    l = vertex.length;
    for (var i = 0; i < l; i++) {
        peta_dasar_bound_des[id].extend(vertex[i]);
    }

    peta_dasar_path_des[id] = new google.maps.Polygon({
        path: vertex,
        strokeColor: '#111111',
        strokeOpacity: 0.9,
        fillColor: peta_dasar_warna2[indeks % peta_dasar_warna.length],
        strokeWeight: 1
    });
    var marker_ = new MarkerWithLabel({
        position: new google.maps.LatLng(0, 0),
        draggable: false,
        raiseOnDrag: false,
        map: peta_dasar_map,
        labelContent: wilayah.desa,
        labelAnchor: new google.maps.Point(30, 20),
        labelClass: "labels", // the CSS class for the label
        labelStyle: {opacity: 1.0},
        icon: "http://placehold.it/1x1",
        visible: false
    });
    google.maps.event.addListener(peta_dasar_path_des[id], "mousemove", function(event) {
        marker_.setPosition(event.latLng);
        marker_.setVisible(true);
    });
    google.maps.event.addListener(peta_dasar_path_des[id], "mouseout", function(event) {
        marker_.setVisible(false);
    });
    google.maps.event.addListener(peta_dasar_path_des[id], "click", function(event) {
        var oid = peta_dasar_des_to_hide;
        console.log('desa to val = '+id);
        $('#pilihdesa').val(id);
        desa_klik(oid, id);
    });
    peta_dasar_path_des[id].setMap(peta_dasar_map);
}
function plot_kec(wilayah, indeks) {
    var vertex = [];
    var vertices = wilayah.geo;
    var l = vertices.length;
    for (var i = 0; i < l; i++) {
        vertex[i] = new google.maps.LatLng(vertices[i].LAT, vertices[i].LNG);
    }
    var id = wilayah.id_kec;
    peta_dasar_bound_kec[id] = new google.maps.LatLngBounds();
    l = vertex.length;
    for (var i = 0; i < l; i++) {
        peta_dasar_bound_kec[id].extend(vertex[i]);
    }

    peta_dasar_path_kec[id] = new google.maps.Polygon({
        path: vertex,
        strokeColor: '#555555',
        strokeOpacity: 0.8,
        fillColor: peta_dasar_warna[indeks % peta_dasar_warna.length],
        strokeWeight: 2
    });
    var marker_ = new MarkerWithLabel({
        position: new google.maps.LatLng(0, 0),
        draggable: false,
        raiseOnDrag: false,
        map: peta_dasar_map,
        labelContent: wilayah.kecamatan,
        labelAnchor: new google.maps.Point(30, 20),
        labelClass: "labels", // the CSS class for the label
        labelStyle: {opacity: 1.0},
        icon: "http://placehold.it/1x1",
        visible: false
    });
    google.maps.event.addListener(peta_dasar_path_kec[id], "mousemove", function(event) {
        marker_.setPosition(event.latLng);
        marker_.setVisible(true);
    });
    google.maps.event.addListener(peta_dasar_path_kec[id], "mouseout", function(event) {
        marker_.setVisible(false);
    });
    google.maps.event.addListener(peta_dasar_path_kec[id], "click", function(event) {
        var oid = peta_dasar_kec_to_hide;
        $('#pilihkecamatan').val(id);
        kecamatan_klik(oid, id);
    });
    peta_dasar_path_kec[id].setMap(peta_dasar_map);
}
function desa_klik(oid,nid){
    load_potensi_desa(peta_dasar_kec_to_hide,nid);
}
function kecamatan_klik(oid, nid) {
    if (peta_dasar_path_kec.hasOwnProperty(oid)) {
        peta_dasar_path_kec[oid].setMap(peta_dasar_map);
    }
    peta_dasar_kec_to_hide = nid;
    //peta_dasar_path_kec[id].setMap(null);
    peta_dasar_draw_poly_kec();
    console.log('oid = ' + oid + ', nid = ' + nid);
    load_list_desa(nid);
    load_potensi_kecamatan(nid);
}

function load_list_desa(id_kec){
    $('#pilihdesa').html('');
    $.ajax({
        type: "POST",
        url: peta_dasar_site_url + "/home/get_list_desa/" + id_kec,
        dataType: 'json',
        success: function(json) {
            $('#pilihdesa').append('<option value="0">SEMUA DESA</option>');
            var l = json.length;
            for(var i=0;i<l;i++){
                $('#pilihdesa').append('<option value="'+json[i].desa_no+'">'+json[i].desa+'</option>');
            }
        }
    });
}

function load_potensi_kecamatan(id_kec) {
    $('#kotakdata').css('display', 'none');
    $.ajax({
        type: "POST",
        url: peta_dasar_site_url + "/home/get_potensi_desa/" + id_kec,
        success: function(rsp) {
            if (rsp.length > 0) {
                $('#kotakdata').html(rsp);
                $('#kotakdata').css('display', '');
            }
        }
    });
}
function load_potensi_desa(id_kec, id_des) {
    $('#kotakdata').html('');
    $.ajax({
        type: "POST",
        url: peta_dasar_site_url + "/home/get_potensi_desa/" + id_kec + '/' + id_des,
        success: function(rsp) {
            if (rsp.length > 0) {
                $('#kotakdata').html(rsp);
                $('#kotakdata').css('display', '');
            }
        }
    });
}