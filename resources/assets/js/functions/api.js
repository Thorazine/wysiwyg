import axios from 'axios'
import asset from './asset.js';

const api = {
    // docGet (data) {
    //     return axios.get(asset('api/documents/get'), {params: data});
    // },
    mediaGet(data) {
    	return axios.get('http://localhost/wysiwyg/public/api/media', {params: data});
    }
}

export default api;
