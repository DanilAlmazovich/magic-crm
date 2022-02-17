export default {
    methods: {
        async geocodeAddress(address) {
            const { data: addresses } = await axios.post('/maps/geocode', {
                address: address.address
            })

            const nearestAddress = addresses.length ? addresses[0] : null

            if (nearestAddress) {
                address.address = nearestAddress.full
                address.latitude = nearestAddress.geocode.latitude
                address.longitude = nearestAddress.geocode.longitude
            }
        }
    }
}
