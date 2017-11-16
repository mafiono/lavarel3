<template>
    <div class="register-form-date">
        <label class="label" :for="name"> {{ label }} </label>
        <input :name="name" :id="name" type="hidden" v-model="form[name]">
        <div class="control">
            <select class="dropdown day" v-model="day" @change="onDateChange()">
                <option value="" disabled selected> {{ dayLabel }} </option>
                <option v-for="n in 31" :value="n | padZero(2)">{{ n | padZero(2) }}</option>
            </select>
            <select class="dropdown month" v-model="month" @change="onDateChange()">
                <option value="" selected disabled> {{ monthLabel }} </option>
                <option v-for="n in 12" :value="n | padZero(2)">{{ n | padZero(2)}}</option>
            </select>
            <select class="dropdown year" v-model="year" @change="onDateChange()">
                <option value="" selected disabled> {{ yearLabel }} </option>
                <option v-for="n in yearsCount" :value="startingYear - n + 1">{{ startingYear - n + 1}}</option>
            </select>
            <i class="error-icon cp-exclamation-circle" v-if="form.errors.has(name)"></i>
            <span class="error-message" v-if="form.errors.has(name)"> {{ form.errors.get(name) }} </span>
        </div>
    </div>
</template>

<script>
    import filters from '../js/filters'

    export default {
        data () {
            return {
                day: '',
                month: '',
                year: ''
            }
        },
        methods: {
            onDateChange () {
                if (this.day === '' || this.month === '' || this.year === '') {
                    return
                }

                this.form[this.name] = `${this.year}-${this.month}-${this.day}`
            },
            parseDate(date) {
                let dateFragments = date.split('-');

                const dateIsComplete = dateFragments.length === 3;

                this.year = dateIsComplete ? dateFragments[0] : '';
                this.month = dateIsComplete ? dateFragments[1] : '';
                this.day = dateIsComplete ? dateFragments[2] : '';
            }
        },
        filters: {
            padZero: function (value, n) {
                return filters.padZero(value, n)
            }
        },
        props: [
            'form',
            'label',
            'name',
            'placeholder',
            'dayLabel',
            'monthLabel',
            'yearLabel',
            'startingYear',
            'yearsCount'
        ],
        mounted() {
            this.parseDate(this.form[this.name]);

            this.$watch(`form.${this.name}`, () => {
                this.parseDate(this.form[this.name]);
            });
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/common/variables';
    @import '../../../sass/common/register-form-control';

    .register-form-date {
        @extend .register-form-control;

        .label {
            @media (max-width: $mobile-screen-width) {
                display: block;
                width: 100%;
                text-align: left;
                font-size: 12px !important;
                font-weight: bold;
                line-height: normal;
                padding: 0 5px;
                margin-bottom: -5px;
            }
        }

        .dropdown {
            color: #5A5E64;
            padding: 5px 10px;
            font-family: 'Open Sans', 'Droid Sans', sans-serif;
            font-size: 13px;
            width: 75%;
            height: 30px;
            border-radius: 0 !important;
            background-color: #EBEEF3;
            border: 1px solid #B9C4D3;

            &:focus {
                border-color: #536883;
            }

            &.day, &.month, &.year {
                width: 24.3%;

                @media (max-width: $mobile-screen-width) {
                    float: left;
                    width: 32%;

                    &:not(:first-child) {
                        margin-left: 2%;
                    }
                }
            }
        }
    }
</style>
