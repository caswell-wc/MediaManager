import axios from "axios";
import "regenerator-runtime/runtime";

/**
 * The API service to manage files
 */
export default class FilesService {

    /**
     * Get the files for the currently logged in user
     * @returns {Promise<any>}
     */
     async getFiles() {
         const { data } = await axios.get('/files');
         return data;
    }

    /**
     * Upload a new file
     * @param name The name of the file
     * @param file The file itself
     * @returns {Promise<any>}
     */
    async uploadFile(name, file) {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('file', file);
        const { data } = await axios.post('/files', formData);
        return data;
    }

    /**
     * Delete the file with the given id
     * @param id
     * @returns {Promise<AxiosResponse<any>>}
     */
    async deleteFile(id) {
        return axios.delete('/files/' + id);
    }
}
