<template>
    <div class="dashboard-component col-12 col-lg-11 col-md-11" style='max-width: 1024px !important;'>
        <h5 class="mb-5 text-center header-label">Legal documentation</h5>

        <div class="d-flex row justify-content-around">
            <div class="document mr-1 ml-1" v-for="(document, index) in legalDocumentation" :key="index">
                <div class="document-content pt-3 pb-3 w-100 d-flex">
                    <span class="document-content-title mr-auto mr-2" :title='document.title'>{{ document.title }}</span>
                    <button class='document-content-btn ml-auto' @click="downloadFile(document.file)">Download PDF</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'LegalDocumentation',
    data() {
        return {
            legalDocumentation: [
                {title: 'Client Agreement', file: 'client-agreement'},
                {title: 'Order Execution Policy', file: 'order-exe-policy'},
                {title: 'Conflicts Of Interest Policy', file: 'conflicts-interest-policy'},
                {title: 'Investor Compensation Fund Notice (English)', file: 'investor-fund-notice-english'},
                {title: 'Investor Compensation Fund Notice (Greek)', file: 'investor-fund-notice-greek'},
                {title: 'Client Categorisation Notice', file: 'client-cat-notice'},
                {title: 'FxPro Wallet', file: 'fxpro-wallet'},
                {title: 'RTS 27 Execution Data Q4 2018', file: 'rts-27-exe-data-q4-2018'},
                {title: 'Contracts For Difference (CFDs)', file: 'contracts-difference-cfds-esma-eba'},
                {title: 'RTS 27 Execution Data Q3 2018', file: 'rts-27-exe-data-q3-2018'},
                {title: 'Complaints Handling Procedure', file: 'complains-handling-procedure'},
                {title: 'RTS 27 Execution Data Q2 2018', file: 'rts-27-exe-data-q2-2018'},
                {title: 'Risk Disclosure Notice', file: 'risk-disclosure-notice'},
                {title: 'RTS 27 Execution Data Q1 2018', file: 'rts-27-exe-data-q1-2018'},
                {title: 'Key Investor Information Document', file: 'key-investor-info-doc'},
                {title: 'RTS 27 Execution Data Q1 2019', file: 'rts-27-exe-data-q1-2019'},
                {title: 'Guide To Investing (Issued By ESMA)', file: 'guide-investing-esma'},
                {title: 'RTS 27 Execution Data Q2 2019', file: 'rts-27-exe-data-q2-2019'},
                {title: 'Disclosure Notice (Financial Services)', file: 'disclosure-notice-finan-services-board'},
                {title: 'RTS 27 Execution Data Q3 2019', file: 'rts-27-exe-data-q3-2019'},
                {title: 'Pillar 3 Disclosures', file: 'pillar-3-disclosures'},
                {title: 'RTS 27 Execution Data Q4 2019', file: 'rts-27-exe-data-q4-2019'},
                {title: 'RTS 27 Execution Data Q1 2020', file: 'rts-27-exe-data-q1-2020'},
                {title: 'RTS 27 Execution Data Q2 2020', file: 'rts-27-exe-data-q2-2020'},
                {title: 'RTS 27 Execution Data Q3 2020', file: 'rts-27-exe-data-q3-2020'},
                {title: 'RTS 27 Execution Data Q4 2020', file: 'rts-27-exe-data-q4-2020'},
            ]
        }
    },
    methods: {
        downloadFile(fileName) {
            alert(fileName);
        }
    }
}
</script>

<style lang='scss'>
    .document {
        border-top: 1px solid #424a4d;
        max-width: 360px;
        width: 100%;

        .document-content {
            line-height: 1;

            .document-content-title {
                white-space: nowrap; 
                overflow: hidden;
                text-overflow: ellipsis; 
                font-size: 14px !important;
                color: #84949a;
                width: 230px !important;
                line-height: 3;
            }

            .document-content-btn {
                font-size: 14px !important;
                color: #f9fafb;
                border: none;
                border-radius: 100px;
                font-weight: 500;
                transition: .15s ease-in-out;
                background-color: #00b894;
                padding: 6px 22px;
            }

            .document-content-btn:hover {
                background-color: #00a181;
            }
        }
    }

@media only screen and (min-width: 426px) and (max-width: 768px) {
    .document {
        max-width: 360px !important;
        .document-content .document-content-title {
            width: 300px !important;
        }
    }
}

@media only screen and (max-width: 768px) {
    .document {
        max-width: 100% !important;
    }
}

@media only screen and (min-width: 375px) and (max-width: 424px) {
    .document .document-content .document-content-title {
        width: 200px !important;
    }
}

@media only screen and (min-width: 320px) and (max-width: 374px) {
    .document .document-content .document-content-title {
        width: 160px !important;
    }
}
</style>