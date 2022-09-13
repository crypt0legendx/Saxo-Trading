export default function currency_locale (input, symb) {
    return parseFloat(input).toLocaleString('en-US', { style: 'currency', currency: symb });
}
