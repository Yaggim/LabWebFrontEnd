const urlMarcaJSON = '../admin/db/marca.json'; 
const urlModeloJSON = '../admin/db/modelo.json';
const urlCategoriaJSON = '../admin/db/categoria.json';

export const marca = async () => {

    const response = await fetch(urlMarcaJSON);
    const MarcaJSON = await response.json();
    return MarcaJSON;
};

export const modelo = async () => {

    const response = await fetch(urlModeloJSON);
    const ModeloJSON = await response.json();
    return ModeloJSON;
};

export const categoria = async () => {

    const response = await fetch(urlCategoriaJSON);
    const CategoriaJSON = await response.json();
    return CategoriaJSON;
};