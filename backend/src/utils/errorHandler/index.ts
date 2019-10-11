import { ErrorRequestHandler } from 'express';

import { ApiError, formatErrors } from 'app/utils/jsonapi/responses/errors';

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const errorHandler: ErrorRequestHandler = (err, _req, res, _next): void => {
  if (err instanceof ApiError) {
    res
      .status(err.status)
      .json(formatErrors([
        err,
      ]));
  }

  throw err;
};

export default errorHandler;
