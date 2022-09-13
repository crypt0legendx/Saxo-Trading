<template :key="windID">
  <v-container id='news-container' class="w-100 h-100 col-12 fluid pa-0" style="height: 1px;">
    <!-- SENDING TOPBAR !-->
     <portal :to="windID">
      <i class="font-icon icon_news"></i>
      <span>News</span>
    </portal>
    <!-- END OF TOPBAR !-->

    <div id='news-filter' class="w-100">
      <b-form-select
      id='news-select'
      class='no-focus-outline'
      v-model="selectedCategory"
      :options="newsCategories"
      :disabled='!newsJson.length'
      @change="showNews"
      ></b-form-select>
    </div>


    <b-skeleton v-show="!loaded" class="no-bg h-100" width="100%"></b-skeleton>


    <div id='news-data' class="m-0 p-0 mt-3 pb-5">
      <div v-for="article_ in newsJson" :key="article_">
        <div v-html="article(article_)"></div>
      </div>
    </div>
  </v-container>
</template>


<style lang="scss">
#news-container {
  padding: 20px !important;

  #news-filter {
    #news-select {
      cursor: pointer;
      font-size: 14px;
      border-radius: 2px;
      border: none;
    }
  }

  #news-data {
    .article-component {
      font-size: 13px !important;
      border-bottom: 1px solid #424242;

      .article-title {
        a {
          color: #33a7f9 !important;
          font-weight: 400 !important;
        }

        a:hover {
          text-decoration: none;
        }
      }

      .article-details {
        line-height: 1 !important;
      }

      .article-content {
        img {
          width: 100%;
          height: auto;
          max-width: 800px;
          max-height: 500px;
          margin: 5px auto !important;
        }
      }
    }
  }


}
</style>

<script>
import newsCategoriesJson from '../../json/news_categories.json';

export default {
  name : "News",


  data () {
    return {
      loaded: false,
      newsJson: [],
      filteredNews: [],
      selectedCategory: 0,
      newsCategories: [
        { value: 0, text: 'All categories' }
      ],
      controls : {
        left : `
        <i style="margin-left:2px; margin-right:4px; font-size:16px;" class="v-icon notranslate mdi mdi-newspaper-variant-multiple-outline theme--light"></i>
        <span style="font-size:13px;">News</span>

        `,

        right : ``,
      },
    }
  },

  props : {
    windID : Number,
  },


  methods : {
    article(article) {
      if(this.selectedCategory === 0 || article.categories[0] === this.selectedCategory) {
        const category = this.newsCategories.find(o => o.value == article.categories[0]);
        const [month, date, year] = new Date(article.date_gmt).toLocaleDateString("en-US").split("/");
        const [hour, minute] = new Date(article.date_gmt).toLocaleTimeString("en-US").split(/:| /);

        return `
        <div class='article-component' id='article-${article.id}'>
        <h5 class='article-title'><a href='${article.link}' target='_blank'>${article.title.rendered}</a></h5>
        <p class='article-details'>
        ${date}.${month}.${year} / <label style='color: #277fc2;'>${hour >= 9 ? hour : `0${hour}`}:${minute}</label> Source: <b>FXPro News</b>
        </br>Category: ${category != undefined ? category.text : 'None'}
        </p>
        <div class='article-content m-0 p-0'>
        ${article.content.rendered}
        </div>
        </div>
        `;
      }
    },
  },

  created() {
    this.$emit('sendControls', this.controls)
  },
  mounted() {
    let th = this
    this.$http.get('https://fxpro.news/wp-json/wp/v2/posts?per_page=20')
    .then((response) => {
      const categoryIDs = new Set([...response.data.map(obj => obj.categories[0])]);

      categoryIDs.forEach((cID) => {
        const {id, name} = newsCategoriesJson.find(obj => obj.id === cID);
        this.newsCategories.push({value: id, text: name});
      });

      this.newsJson = response.data;
      th.loaded = true;
    })
    .catch(err => {});
  }
}
</script>
