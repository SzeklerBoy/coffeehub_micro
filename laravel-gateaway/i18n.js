import { createI18n } from 'vue-i18n';
import en from './resources/locales/en.json';
import hu from './resources/locales/hu.json';
import ro from './resources/locales/ro.json';

const browserLocale = navigator.language.split('-')[0];

// Create i18n instance with options
const i18n = createI18n({
    legacy: false, // use composition API
    locale: browserLocale,  // active language in the project
    fallbackLocale: 'en',
    messages: {en, hu, ro}
});

export default i18n;
