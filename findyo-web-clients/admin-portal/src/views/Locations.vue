<template>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Locations</h5>
      <h6 class="card-subtitle mb-5 text-muted">Add, edit or remove locations</h6>
      <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">Method</div>
                <div class="col-md-8">
                    <select name="" id="" class="form-control" v-model="selectedMethod" @change="onMethodChange($event)">
                        <option v-for="method in methods" :key="method.value" v-bind:value="method.value" :disabled="countries.length > 0 && method.value === 1">{{method.text}}</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3" v-if="countries.length > 0 && selectedMethod > 1">
                <div class="col-md-4">Country</div>
                <div class="col-md-8">
                    <select name="" id="" class="form-control" v-model="selectedCountry" @change="onCountryChange($event)" disabled>
                        <option v-for="country in countries" :key="country.value" v-bind:value="country.value">{{country.text}}</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3" v-if="provinces.length > 0 && selectedCountry !== null && selectedMethod > 2">
                <div class="col-md-4">Province</div>
                <div class="col-md-8">
                    <select name="" id="" class="form-control" v-model="selectedProvince" @change="onProvinceChange($event)">
                        <option v-for="province in provinces" :key="province.value" v-bind:value="province.value">{{province.text}}</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3" v-if="districts.length > 0 && selectedProvince !== null && selectedMethod > 3">
                <div class="col-md-4">District</div>
                <div class="col-md-8">
                    <select name="" id="" class="form-control col-md-12" v-model="selectedDistrict">
                        <option v-for="district in districts" :key="district.value" v-bind:value="district.value">{{district.text}}</option>
                    </select>
                </div>
            </div>
            <div class="row inptName mt-3">
                <div class="col-md-4">{{selectedType}} name in English</div>
                <div class="col-md-8">
                    <input type="text" :disabled="!isInputTextEnable" class="form-control col-md-12" v-model="name_en" />
                </div>
            </div>
            <div class="row inptName mt-3">
                <div class="col-md-4">{{selectedType}} name in Sinhala</div>
                <div class="col-md-8">
                    <input type="text" :disabled="!isInputTextEnable" class="form-control col-md-12" v-model="name_sn" />
                </div>
            </div>
            <div class="row inptName mt-3">
                <div class="col-md-4">{{selectedType}} name in Tamil</div>
                <div class="col-md-8">
                    <input type="text" :disabled="!isInputTextEnable" class="form-control col-md-12" v-model="name_tm" />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md">
                    <button type="button" class="btn btn-secondary btn-lg col-md-12" @click="clear">Clear</button>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary btn-lg col-md-12" @click="save">Save</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <ul class="list-group">
                    <li v-show="selectedMethod >= 3 ? country.value === selectedCountry : true" class="list-group-item list-group-item-action" :class="{'active': country.value === selectedCountry}" v-for="(country, countryIdx) in countries" :key="countryIdx" @click.stop="selectCountry(country.value)">{{country.text}}
                        <ul class="list-group">
                            <li v-show="selectedMethod >= 4 ? province.value === selectedProvince : true" class="list-group-item list-group-item-action" :class="{'active': province.value === selectedProvince}" v-for="(province, provinceIdx) in provinces" :key="provinceIdx" @click.stop="selectProvince(province.value)">{{province.text}}
                                <ul v-if="selectedMethod === 4" class="list-group">
                                    <li class="list-group-item list-group-item-action" :class="{'active': district.value === selectedDistrict}" v-for="(district, districtIdx) in districts" :key="districtIdx" @click.stop="selectDistrict(district.value)">{{district.text}}</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'locations',
  data () {
    return {
      message: 'Add Locations',
      methods: [
        { value: 1, text: 'Create Country' },
        { value: 2, text: 'Create Province' },
        { value: 3, text: 'Create District' },
        { value: 4, text: 'Create City' }
      ],
      selectedMethod: 1,
      selectedCountry: null,
      selectedProvince: null,
      selectedDistrict: null,
      countries: [],
      provinces: [],
      districts: [],
      name_en: '',
      name_sn: '',
      name_tm: ''
    }
  },
  props: {
    db: {
      type: Object,
      default () {
        return {}
      }
    }
  },
  components: {
  },
  computed: {
    selectedType () {
      let val
      if (this.selectedMethod === 1) {
        val = 'Country'
      } else if (this.selectedMethod === 2) {
        val = 'Province'
      } else if (this.selectedMethod === 3) {
        val = 'District'
      } else if (this.selectedMethod === 4) {
        val = 'City'
      }
      return val
    },
    isInputTextEnable () {
      let state = false
      if (this.selectedMethod === 1) {
        state = true
      } else if (this.selectedMethod === 2 && this.selectedCountry !== null) {
        state = true
      } else if (this.selectedMethod === 3 && this.selectedCountry !== null && this.selectedProvince !== null) {
        state = true
      } else if (this.selectedMethod === 4 && this.selectedCountry !== null && this.selectedProvince !== null && this.selectedDistrict !== null) {
        state = true
      }
      return state
    }
  },
  methods: {
    clear () {
      this.name_en = ''
      this.name_sn = ''
      this.name_tm = ''
      this.countries = []
      this.provinces = []
      this.districts = []
      this.selectedCountry = null
      this.selectedProvince = null
      this.selectedDistrict = null
      this.selectedMethod = 1
    },
    clearFields () {
      this.name_en = ''
      this.name_sn = ''
      this.name_tm = ''
    },
    save () {
      const selected = this.selectedMethod
      switch (selected) {
        case 1:
          this.saveCountry()
          break
        case 2:
          this.saveProvince()
          break
        case 3:
          this.saveDistrict()
          break
        case 4:
          this.saveCity()
          break
      }
    },
    saveToDB () {

    },
    onMethodChange () {
      this.name_en = ''
      this.name_sn = ''
      this.name_tm = ''
      if (this.selectedMethod === 1) {
        console.log(1)
      } else if (this.selectedMethod === 2) {
        this.getCountries()
      } else if (this.selectedMethod === 3) {
        this.getCountries()
        if (this.selectedCountry) {
          this.getProvincesToCountry(this.selectedCountry)
        }
      } else if (this.selectedMethod === 4) {
        this.getCountries()
        if (this.selectedCountry) {
          this.getProvincesToCountry(this.selectedCountry)
        }
        if (this.selectedProvince) {
          this.getDistrictsToProvince(this.selectedProvince)
        }
      } else {
        this.countries = []
      }
    },
    selectCountry (val) {
      this.selectedCountry = val
    },
    selectProvince (val) {
      this.selectedProvince = val
    },
    selectDistrict (val) {
      this.selectedDistrict = val
    },
    onCountryChange () {
      if (this.selectedMethod === 3) {
        this.getProvincesToCountry(this.selectedCountry)
      } else if (this.selectedMethod === 4) {
        this.getProvincesToCountry(this.selectedCountry)
      }
    },
    onProvinceChange () {
      if (this.selectedMethod > 3) {
        this.getDistrictsToProvince(this.selectedProvince)
      }
    },
    getCountries () {
      this.provinces = []
      this.db.collection('Locations').where('is_country', '==', true).get().then(snapShot => {
        this.countries = []
        snapShot.forEach(doc => {
          // debugger
          this.countries.push({
            value: doc.id,
            text: doc.data().name_en
          })
          // console.log(doc.id, " => ", doc.data());
        })
      }).catch(error => {
        console.log(error)
      })
    },
    getProvincesToCountry (countryID) {
      this.db.collection('Locations').where('is_province', '==', true).where('country_id', '==', countryID).get().then(snapShot => {
        snapShot.forEach(doc => {
          this.provinces.push({
            value: doc.id,
            text: doc.data().name_en
          })
          // console.log(doc.id, " => ", doc.data());
        })
      }).catch(error => {
        console.log(error)
      })
    },
    getDistrictsToProvince (provinceID) {
      this.districts = []
      this.db.collection('Locations').where('is_district', '==', true).where('province_id', '==', provinceID).get().then(snapShot => {
        snapShot.forEach(doc => {
          this.districts.push({
            value: doc.id,
            text: doc.data().name_en
          })
          console.log(doc.id, ' => ', doc.data())
        })
      }).catch(error => {
        console.log(error)
      })
    },
    saveCountry () {
      this.db.collection('Locations').add({
        is_country: true,
        name_en: this.name_en.trim(),
        name_si: this.name_sn.trim(),
        name_ta: this.name_tm.trim()
      }).then(() => {
        this.clear()
        alert('Country added successfully')
      }).catch((error) => {
        alert('Error in adding the country')
        console.log(error)
      })
    },
    saveProvince () {
      this.db.collection('Locations').add({
        is_province: true,
        country_id: this.selectedCountry,
        name_en: this.name_en.trim(),
        name_si: this.name_sn.trim(),
        name_ta: this.name_tm.trim()
      }).then(() => {
        alert('Province added successfully')
        this.initialLoad()
        this.clearFields()
      }).catch((error) => {
        alert('Error in adding the province')
        console.log(error)
      })
    },
    saveDistrict () {
      this.db.collection('Locations').add({
        is_district: true,
        country_id: this.selectedCountry,
        province_id: this.selectedProvince,
        name_en: this.name_en.trim(),
        name_si: this.name_sn.trim(),
        name_ta: this.name_tm.trim()
      }).then(() => {
        alert('District added successfully')
        this.clearFields()
      }).catch((error) => {
        alert('Error in adding the district')
        console.log(error)
      })
    },
    saveCity () {
      this.db.collection('Locations').add({
        is_city: true,
        country_id: this.selectedCountry,
        province_id: this.selectedProvince,
        district_id: this.selectedDistrict,
        name_en: this.name_en.trim(),
        name_si: this.name_sn.trim(),
        name_ta: this.name_tm.trim()
      }).then(() => {
        alert('City added successfully')
        this.clear()
      }).catch((error) => {
        alert('Error in adding the city')
        console.log(error)
      })
    },
    initialLoad () {
      this.provinces = []
      this.db.collection('Locations').where('is_country', '==', true).get().then(snapShot => {
        this.countries = []
        snapShot.forEach(doc => {
          this.countries.push({
            value: doc.id,
            text: doc.data().name_en
          })
        })
        if (this.countries.length > 0) {
          this.selectedMethod = 2
          this.selectCountry(this.countries[0].value)
        }
      }).catch(error => {
        console.log(error)
      })
    }
  },
  mounted () {
    this.initialLoad()
  }
}
</script>
