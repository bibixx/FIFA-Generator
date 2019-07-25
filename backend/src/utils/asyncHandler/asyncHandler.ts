import { RequestHandler } from 'express';

const asyncHandler = function ah(...handlers: RequestHandler[]): RequestHandler {
  const innerFn: RequestHandler = async (req, res, next): Promise<void> => {
    try {
      await Promise.all(
        handlers.map((handler): Promise<void> => handler(req, res, next)),
      );
    } catch (error) {
      next(error);
    }
  };

  return innerFn;
};

export default asyncHandler;
