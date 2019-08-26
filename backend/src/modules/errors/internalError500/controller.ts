import { Request, Response, NextFunction } from 'express';
import logger from '../../../utils/logger';

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const internalError = (err: Error, _req: Request, res: Response, _next: NextFunction): void => {
  logger.error(`An error occurred but it was caught. ${err.stack}`);

  res
    .status(500)
    .json({
      error: 'Internal server error',
    });
};

export default internalError;
