<?php
/**
 * @author Alexander.Lisachenko
 * @date 14.07.2014
 */

namespace Protocol\FCGI\Record;

use Protocol\FCGI;
use Protocol\FCGI\Record;

class BeginRequest extends Record
{
    /**
     * The role component sets the role the Web server expects the application to play.
     * The currently-defined roles are:
     *   FCGI_RESPONDER
     *   FCGI_AUTHORIZER
     *   FCGI_FILTER
     *
     * @var integer
     */
    protected $role = FCGI::UNKNOWN_ROLE;

    /**
     * The flags component contains a bit that controls connection shutdown.
     *
     * flags & FCGI_KEEP_CONN:
     *   If zero, the application closes the connection after responding to this request.
     *   If not zero, the application does not close the connection after responding to this request;
     *   the Web server retains responsibility for the connection.
     *
     * @var integer
     */
    protected $flags;

    /**
     * Reserved data, 5 bytes maximum
     *
     * @var string
     */
    protected $reserved1;

    /**
     * Method to unpack the payload for the record
     *
     * @param Record|self $self Instance of current frame
     * @param string $data Binary data
     *
     * @return Record
     */
    protected static function unpackPayload(Record $self, $data)
    {
        list(
            $self->role,
            $self->flags,
            $self->reserved1
        ) = array_values(unpack("nrole/Cflags/a5reserved", $data));

        return $self;
    }

    /**
     * Implementation of packing the payload
     *
     * @return string
     */
    protected function packPayload()
    {
        return pack(
            "nCa5",
            $this->role,
            $this->flags,
            $this->reserved1
        );
    }
}