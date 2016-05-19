CREATE 
VIEW `bhp_distribusi_item` AS
    SELECT 
        `inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai` AS `id_mst_inv_barang_habispakai`,
        `inv_inventaris_habispakai_distribusi_item`.`batch` AS `batch`,
        `inv_inventaris_habispakai_distribusi`.`tgl_distribusi` AS `tgl_distribusi`,
        `inv_inventaris_habispakai_distribusi`.`code_cl_phc` AS `code_cl_phc`
    FROM
        (`inv_inventaris_habispakai_distribusi_item`
        JOIN `inv_inventaris_habispakai_distribusi` ON ((`inv_inventaris_habispakai_distribusi_item`.`id_inv_inventaris_habispakai_distribusi` = `inv_inventaris_habispakai_distribusi`.`id_inv_inventaris_habispakai_distribusi`)))