interface JsonApiResponse<T> {
  data: T;
}

export const formatObject = <T>(data: T): JsonApiResponse<T> => ({
  data,
});
