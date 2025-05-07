import { z } from "zod";

export const addCategoryForm = z.object({
    name: z.string().min(1, "category name is required"),
    url_name: z.string().min(1, "category name is required"),
    info: z.string().min(1, "category info is required"),
    img_url: z.string().url("Image URL must be a valid URL"),
  });

export const editCategoryForm = z.object({
  id: z.string().min(1, "category id is required"), 
  name: z.string().min(1, "category name is required"),
  url_name: z.string().min(1, "category name is required"),
  info: z.string().min(1, "category info is required"),
  img_url: z.string().url("Image URL must be a valid URL"),
});

