import FilesService from "../../resources/js/services/FilesService";
import axios from "axios";

jest.mock('axios');
const filesService = new FilesService();

describe('FilesService', () => {
    it('Accesses the get files endpoint', async () => {
        const response = {data: [
            {
                name: 'Computer'
            },
            {
                name: 'World'
            }
        ]};
        axios.get.mockImplementationOnce(() => Promise.resolve(response));
        await expect(filesService.getFiles()).resolves.toEqual(response.data);
        expect(axios.get).toHaveBeenCalledWith('/files');
    });

    it ('Can upload a file', async () => {
        const response = {
            data: {
                id: 1,
                name: 'Computer',
                path: 'something.jpeg',
                type: 'image',
                user_id: 1
            }
        }
        axios.post.mockImplementationOnce(() => Promise.resolve(response));
        await expect(filesService.uploadFile('Computer', 'file')).resolves.toEqual(response.data);
        const formData = new FormData();
        formData.append('name', "Computer");
        formData.append('file', 'file');
        expect(axios.post).toHaveBeenCalledWith('/files', formData);
    });

    it('Can delete a file', async () => {
        axios.delete.mockImplementationOnce(() => Promise.resolve());
        await filesService.deleteFile(1);
        expect(axios.delete).toHaveBeenCalledWith('/files/1');
    });
})
