import mongoose from 'mongoose';
import shortid from 'shortid';

import includeProperty from 'app/utils/includeProperty';

export interface TournamentsResponse {
  id: string;
  type: string;
  attributes: {
    name?: string;
    secret?: string;
    adminToken?: string;
  };
}

export interface ITournament extends mongoose.Document {
  uuid: string;
  name?: string;
  adminToken: string;
  secret: string;
  toResponse: (
    this: ITournament,
    adminToken?: string,
    showAdminToken?: boolean
  ) => TournamentsResponse;
}

const TournamentSchema = new mongoose.Schema({
  uuid: {
    type: String,
    index: true,
    default: (): string => shortid.generate(),
    unique: true,
  },
  name: {
    type: String,
  },
  adminToken: {
    type: String,
    default: (): string => shortid.generate(),
  },
  secret: {
    type: String,
    default: (): string => 'MY_VERY_SECRET_SECRET',
  },
});

TournamentSchema
  .method(
    'toResponse',
    function(adminToken, showAdminToken = false): TournamentsResponse {
      const tournament = this;

      const showSecret = adminToken === tournament.adminToken;

      return ({
        id: tournament.uuid,
        type: 'tournament',
        attributes: {
          name: tournament.name,
          ...includeProperty(showSecret, 'secret', tournament.secret),
          ...includeProperty(showAdminToken, 'adminToken', tournament.adminToken),
        },
      });
    } as ITournament['toResponse'],
  );

const Tournament = mongoose.model<ITournament>('Tournament', TournamentSchema);

export default Tournament;
