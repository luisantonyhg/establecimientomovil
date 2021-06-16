import { OpenStreetMapProvider } from "leaflet-geosearch";
const provider = new OpenStreetMapProvider();


document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector("#mapa")) {
        const lat = -12.0058503;
        const lng = -77.0038143;

        const mapa = L.map("mapa").setView([lat, lng], 16);

        // Eliminar pines previos
        let markers = new L.FeatureGroup().addTo(mapa);


        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapa);

        let marker;
        // agregar en pin
        marker = new L.marker([lat, lng], {
            draggable: true,
            autoPan: true
        }).addTo(mapa);

        // agregar el pin a las capas
        markers.addLayer(marker);

        // Geocode Service
        const geocodeService = L.esri.Geocoding.geocodeService();

        // buscador de direcciones
        const buscador = document.querySelector('#formbuscador')
        buscador.addEventListener('blur', buscarDireccion);

        reubicarPin(marker)

        function reubicarPin(marker) {
            //   detectar movimiento del marker

            marker.on("moveend", function(e) {
                console.log(e.target);
                marker = e.target;
                const posicion = marker.getLatLng();

                // centrar automaticamente
                mapa.panTo(new L.LatLng(posicion.lat, posicion.lng));

                // reverse geocoding, cuando el usuario reubica el pin

                geocodeService
                    .reverse()
                    .latlng(posicion, 16)
                    .run(function(error, resultado) {
                        console.log(error);
                        console.log(resultado.address);

                        marker.bindPopup(resultado.address.LongLabel);
                        marker.openPopup();

                        llenarImputs(resultado);
                    });
            });
        }


        function buscarDireccion(e) {
            if (e.target.value.length > 5) {
                console.log("desde buscar direccion");
                provider.search({ query: e.target.value })
                    .then(resultado => {
                        console.log(resultado)
                        if (resultado[0]) {

                            // limpiar los pines
                            markers.clearLayers();


                            geocodeService
                                .reverse()
                                .latlng(resultado[0].bounds[0], 16)
                                .run(function (error, resultado) {

                                    // llenar los inputs
                                    llenarImputs(resultado);

                                    // central el mapa
                                    mapa.setView(resultado.latlng)

                                    // agregar el pin
                                    marker = new L.marker(resultado.latlng, {
                                        draggable: true,
                                        autoPan: true
                                    }).addTo(mapa);
                                    // asignar el contenedor de markers el nuevo pin
                                    markers.addLayer(marker);

                                    // mover el pin
                                    reubicarPin(marker);


                                });
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }
        }

        function llenarImputs(resultado) {
            console.log("desde llenar inputs");
            document.querySelector("#direccion").value =
                resultado.address.Address || '';
            document.querySelector("#colonia").value =
                resultado.address.Neighborhood || "";
            document.querySelector("#lat").value = resultado.latlng.lat || "";
            document.querySelector("#lng").value = resultado.latlng.lng || "";
        }
    }
});
