import { RequestHandler } from 'express';

import { ApiError } from 'app/utils/jsonapi/responses/errors';
import { formatObject } from 'app/utils/jsonapi/responses/object';

import {
  createTournament as createTournamentService,
  getTournaments as getTournamentsService,
  getTournamentById as getTournamentByIdService,
  deleteTournamentById as deleteTournamentByIdService,
  editTournamentById as editTournamentByIdService,
} from 'app/modules/tournaments/service';

import { TournamentsResponse } from 'app/modules/tournaments/model';

export const createTournament: RequestHandler = async (req, res): Promise<void> => {
  const { name }: ({ name?: string }) = req.body;

  const newTournament = await createTournamentService(name);

  const response = newTournament.toResponse(undefined, true);

  res.status(201).json(formatObject(response));
};

export const getTournaments: RequestHandler = async (_req, res): Promise<void> => {
  const tournaments = await getTournamentsService();

  const response = tournaments
    .map((tournament): TournamentsResponse => tournament.toResponse());

  res.json(formatObject(response));
};

export const getTournamentById: RequestHandler = async (req, res): Promise<void> => {
  const {
    uuid: uuidSearchedBy,
  } = req.params;

  const {
    adminToken,
  } = req.query;

  const tournament = await getTournamentByIdService(uuidSearchedBy);

  if (tournament === null) {
    throw new ApiError(404);
  }

  const response = tournament.toResponse(adminToken);

  res.json(formatObject(response));
};

export const deleteTournamentById: RequestHandler = async (req, res): Promise<void> => {
  const uuidSearchedBy = req.params.uuid;

  await deleteTournamentByIdService(uuidSearchedBy);

  res.status(204).send();
};

export const editTournamentById: RequestHandler = async (req, res): Promise<void> => {
  const { uuid: uuidSearchedBy } = req.params;
  const { name }: ({ name?: string }) = req.body;

  if (name === undefined) {
    throw new ApiError(422);
  }

  const editedTournament = await editTournamentByIdService(uuidSearchedBy, name);

  if (editedTournament === null) {
    throw new ApiError(404);
  }

  res.status(202).send();
};
