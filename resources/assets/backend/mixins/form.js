import diff from '../utils/diff';
export default {
  data () {
    return {
      id: null,
      title: '',
      errors: []
    };
  },
  methods: {
    confirm () {
      let method, url;
      if (this.id) {
        method = 'put';
        url = `${this.$options.base.url}/${this.id}`;
      } else {
        method = 'post';
        url = this.$options.base.url;
      }
      this.$http[method](url, diff.diff(this.formData)).then(res => {
        this.$Message.success(`${this.title}成功`);
        this.$emit('on-success');
      }).catch(err => {
        this.errors = err.response.data.errors;
      });
    },
    init () {
      if ((this.$options.isAdd !== undefined && !this.$options.isAdd) || this.$route.name.substring(0, 4) === 'edit') {
        if (this.$options.isAdd === undefined) {
          this.id = this.$route.params.id;
        }
        console.log(this.id);
        this.$http.get(`${this.$options.base.url}/${this.id}`, {
          params: this.$options.base.query
        }).then(res => {
          this.formData = res.data.data;
          diff.save(this.formData);
          this.$emit('on-data', this.formData);
        });
        this.title = `编辑${this.$options.base.title}`;
      } else {
        this.title = `添加${this.$options.base.title}`;
      }
    }
  },
  created () {
    this.init();
  }
};
