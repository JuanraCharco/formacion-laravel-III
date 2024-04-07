import 'ol/ol.css';
import {Map,View} from 'ol';
import TileLayer from 'ol/layer/WebGLTile';
import Group from 'ol/layer/Group';
import BingMaps from 'ol/source/BingMaps';
import TileWMS from 'ol/source/TileWMS';
import * as olProj from 'ol/proj.js';
import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
import {Circle, Fill, Stroke, Style, Text, Icon, RegularShape} from 'ol/style';
import Feature from 'ol/Feature';
import Point from 'ol/geom/Point';
import Polygon from 'ol/geom/Polygon';
import LineString from 'ol/geom/LineString';
import Draw from 'ol/interaction/Draw';
import Select from 'ol/interaction/Select';
import {defaults, FullScreen, ZoomToExtent, ScaleLine, MousePosition} from 'ol/control';
import Overlay from 'ol/Overlay';
import { unByKey } from 'ol/Observable';
import WMTS from 'ol/source/WMTS.js';
import WMTSTileGrid from 'ol/tilegrid/WMTS.js';
import {get as getProjection} from 'ol/proj.js';
import {getTopLeft, getWidth} from 'ol/extent.js';
import {toStringXY} from 'ol/coordinate';
import OSM from 'ol/source/OSM';
import StadiaMaps from 'ol/source/StadiaMaps';

import 'ol-ext/dist/ol-ext.css';
import LayerSwitcher from 'ol-ext/control/LayerSwitcher';


var CSRF_TOKEN = document.querySelector('meta[name=csrf-token]').content;

const projection = getProjection('EPSG:3857');
const projectionExtent = projection.getExtent();
const size = getWidth(projectionExtent) / 256;
const resolutions = new Array(20);
const matrixIds = new Array(20);
for (let z = 0; z < 20; ++z) {
    // generate resolutions and matrixIds arrays for this WMTS
    resolutions[z] = size / Math.pow(2, z);
    matrixIds[z] = z;
}

var map;

// Control LayerSwitcher
let LayerSwitcherCtrl = new LayerSwitcher({
    collapsed: false
});

var baseLayers = new Group(
    {   id: 'baseLayers',
        title: 'Baselayers',
        openInLayerSwitcher: true,
        noSwitcherDelete: true,
        layers:
            [
                new TileLayer({
                    id: 'osm',
                    title: "OpenStreetMap",
                    name: 'osm',
                    opacity: 1,
                    source: new OSM()
                }),
                new TileLayer(
                    {	id: 'ortopnoa',
                        title: "Orto PNOA",
                        name: 'ortopnoa',
                        source: new TileWMS({
                            url: 'https://www.ign.es/wms-inspire/pnoa-ma?',
                            params: {LAYERS: 'OI.OrthoimageCoverage', VERSION: '1.1.1', TILED: true}
                        })
                    }),
            ]
    });

var newGroupLayers = new Group(
    {   id: 'newGroupLayers',
        title: 'New Group',
        openInLayerSwitcher: true,
        noSwitcherDelete: true,
        layers:
            [
                /*
                new TileLayer(
                    {	id: '',
                        title: '',
                        name: '',
                        source: new TileWMS({
                            url: '',
                            params: {LAYERS: '', VERSION: '1.1.1', TILED: true}
                        })
                    }),
                 */
            ]
    });

function resizeWindow(){
    var w = document.documentElement.clientWidth;
    var h = document.documentElement.clientHeight;
    $("#map").css('height',(h-57) + 'px');
    map.updateSize();
}


document.addEventListener("DOMContentLoaded", function(event) {
    f_obj.inicio();
});


var f_obj = {

    inicio: function() {

        window.addEventListener("resize", resizeWindow);

        var center = olProj.transform([-3.627411, 40.007395], 'EPSG:4326', 'EPSG:3857');

        map = new Map({
            target: 'map',
            layers: [baseLayers,/* newGroupLayers */],
            overlays: [],
            controls: [],
            view: new View({
                projection: projection,
                center: center,
                zoom: 7.5,
                minResolution: 0.29858214173896974, //0.07464553543474244,
                maxResolution: 156543.03392804097
            }),
            renderer: 'webgl'
        });

        map.addControl(LayerSwitcherCtrl);

        setTimeout(function(){
            resizeWindow();
        }, 500);

    },

};
export default f_obj;

window.f_obj = f_obj;


window.map = map;
