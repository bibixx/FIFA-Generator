import { RequestHandler } from 'express';
import Tournament from 'app/modules/tournaments/model';
import asyncHandler from 'app/utils/asyncHandler';

import { ApiError } from 'app/utils/jsonapi/responses/errors';

const isAdmin: RequestHandler = asyncHandler(async (req, _res, next): Promise<void> => {
  const {
    uuid,
  } = req.params;

  const {
    adminToken,
  } = req.query;

  const tournament = await Tournament.findOne({ uuid }).select(['adminToken']);

  if (tournament === null) {
    throw new ApiError(404);
  }

  if (adminToken !== tournament.adminToken) {
    throw new ApiError(403);
  }

  next();
});

export default isAdmin;
