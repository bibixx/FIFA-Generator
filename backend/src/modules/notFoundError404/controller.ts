import { RequestHandler } from 'express';

const notFoundError404Controller: RequestHandler = (_req, res): void => {
  res
    .status(404)
    .json({
      error: 'Not found',
    });
};

export default notFoundError404Controller;
